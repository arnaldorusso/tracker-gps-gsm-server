#!/usr/bin/env python2
# -*- coding: utf-8 -*-


#	FORMAT ODBIERANYCH DANYCH
#		latitude,latitudeInd,longitude,longitudeInd,altitude,speed,satellites,pdop,mode
#		5013.2225,N,01903.7918,E,172.3,0.16,4,1.21,D
#
#	INSTRUKCJE
#		!nofix
#
#
#	TEST TCP
#		echo '5013.2225,N,01903.7918,E,172.3,0.16,4,1.21,D' | nc -t localhost 9999
#		echo '!nofix' | nc -t localhost 9999
#	TEST UDP
#		echo '5013.2225,N,01903.7918,E,172.3,0.16,4,1.21,D' | nc -u localhost 9999
#		echo '!nofix' | nc -u localhost 9999	
#
#	ERRORS:
#		ERROR 0	- brak odebrabych danych
#		ERROR 1	- błędny format danych
# 		ERROR 2	- pozycja z GPS nie ustalona
#


import socket
import MySQLdb
import sys
import time



# ----------------------------------------
# konfiguracja

DEBUG = 1								# czy włączyć debugging?
										# będą widoczne różne informacje na ekranie konsoli

#_IP = 'chmurli.dyndns.info'
#_IP = 'localhost'
_PROTOCOL = 'TCP'						# TCP lub UDP
_PORT = 9999
_BUFFER_SIZE = 100
_LOG_FILE = '/tmp/tracker_gsm_gps.log'

# mySQL
_DB_HOST='localhost'
_DB_USER='tracker'
_DB_PASSWD='tracker'
_DB_NAME='tracker_gps_gsm'
_DB_TABLE='tracker_gps_gsm_data'

# ----------------------------------------



def writeToLogFile(addr, string):
	""" pisz do pliku z logami połączeń	"""
	logfile = open(_LOG_FILE, 'a')										# otwórz w trybie APPEND plik z logami	
	text = "%s; %s\t: %s" % ( time.ctime(time.time()), addr, string )	# sformatuj dane do zapisu
	logfile.write(text)													# zapisz do pliku
	logfile.close()														# zamknij plik



def convertToDDEG(latitude, latitudeInd, longitude, longitudeInd):
	"""zamiana współrzędnych na format DDEG - Decimal Degree"""
	
	# szerokość geograficzna
	latitude = round(float(latitude[:2]) + float(latitude[2:])/60.0,10)
	if latitudeInd=="S":
		latitude = -latitude

	# długość geograficzna
	longitude = round(float(longitude[:3]) + float(longitude[3:])/60.0,10)
	if longitudeInd=="W":
		longitude = -longitude

	return latitude,longitude





"""połącz z bazą mySQL"""
try:
	db = MySQLdb.connect (host = _DB_HOST, user = _DB_USER, passwd = _DB_PASSWD, db = _DB_NAME)
	cursor = db.cursor()
	print "Connected to MySQL with success."
except MySQLdb.Error, e:
	print "Could not open MySQL Database: "
	print "Error %d: %s" %(e.args[0], e.args[1])
	print "Exit program."
	sys.exit(1)
		
	


"""otwórz socket do nasłuchiwania"""
try:
	# na jakim protokole otworzyć port?
	if _PROTOCOL == "UDP":
		s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM) 		# utworzenie gniazda UDP
	else:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 		# utworzenie gniazda TCP
	#s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
	s.bind(('', _PORT))												# dowiazanie do portu
	if _PROTOCOL == "TCP":
		s.listen(5)
	print "Socket opened with success."
	print "Listening port",_PORT,"\b..."
except socket.error:
	if s:
		if _PROTOCOL == "TCP":
			s.close()
		print "Could not open socket."
		print "Exit program."
		cursor.close()
		db.close()
		sys.exit(1)



def serverStart():
	"""startuj server"""
	try:
		while 1:
			
			if _PROTOCOL == "UDP":
				receivedData, addr = s.recvfrom(_BUFFER_SIZE)
			else:
				conn,addr = s.accept()						# odebranie polaczenia
				receivedData = conn.recv(_BUFFER_SIZE)		# odebranie danych
			
			if DEBUG:
				print "połączenie od: ", addr
			
			if not receivedData: 						# jeżeli nic nie odebrano
				if DEBUG:
					print "nic nie odebrano"
				writeToLogFile(addr, "ERROR 0: nic nie odebrano\n")
				
				if _PROTOCOL == "TCP":
					conn.close()
				continue
			
			
			# sprawdzamy czy otrzymano instrukcję, zaczynają się one od znaku '!'
			if receivedData[0]=="!":
				
				if DEBUG:
					print "odebrano instrukcję: %s" % ( receivedData )
				

				# brak ustalenia pozycji GPS
				# wysyłany rzadko (np. co minutę) gdy pozycja nie została ustalona
				if receivedData[1:6]=="nofix":
					writeToLogFile(addr, "ERROR 2: pozycja GPS nie ustalona\n")
				
				if _PROTOCOL == "TCP":
					conn.close()
				continue
			
			
			# sprawdzamy czy otrzymane dane mają poprawny format
			if receivedData.count(",") != 8:
				# jeżeli nie mają to pomijamy 
				writeToLogFile(addr, "ERROR 1: "+receivedData)
				if DEBUG:
					print "odebrano dane w błędnym formacie: %s" % ( receivedData )
				
				if _PROTOCOL == "TCP":
					conn.close()
				continue
			
			writeToLogFile(addr, receivedData)			# zapisz dane do loga
			
			# rozbij stringa na poszczególne zmienne
			latitude,latitudeInd,longitude,longitudeInd,altitude,speed,satellites,pdop,mode = receivedData.split(",")
			
			
			# zamiana jednostki prędkości z węzłów/h na km/h
			speed=round(float(speed)*1.852,2)
			
			# zamień współrzędne na format dla Google Maps
			latitude,longitude = convertToDDEG(latitude, latitudeInd, longitude, longitudeInd)
	
			
			if DEBUG:
				print "odebrane: %sprzetworzone: %s %s %s %s %s %s %s %s %s" % \
					( receivedData, latitude, latitudeInd, longitude, longitudeInd, altitude, speed, satellites, pdop, mode )
				
			
			# dodaj otrzymane dane do bazy danych
			cursor.execute ("""
				INSERT INTO tracker_gps_gsm_data
				(id, date, latitude, longitude, altitude, speed, satellites, pdop, mode)
				VALUES (NULL , CURRENT_TIMESTAMP, %s, %s, %s, %s, %s, %s, %s)""",
				(latitude, longitude, altitude, speed, satellites, pdop, mode))
			db.commit()
			
			
			
	
			#conn.send(time.ctime(time.time()))		# wyslanie danych do klienta
			if _PROTOCOL == "TCP":
				conn.close()						# zamknięcie połączenia
		
			
			
	
	except KeyboardInterrupt:
		print "\nExit server."
		# zamknij socket i bazę
		if s:
			if _PROTOCOL == "TCP":
				s.close()
		cursor.close()
		db.close()


if __name__ == '__main__':
	#print "Tracker GPS-GSM"
	#print "author: Bartosz Chmura"
	serverStart()










