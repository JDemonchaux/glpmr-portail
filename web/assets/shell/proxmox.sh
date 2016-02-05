#!/bin/bash

#Scrit de cr�ation de vm en dmz
#Connexion en ssh au serveur proxmox 172.31.10.1


osTemplate = $1
ip = $2
nomMachine = $3
mdp = $4
id = $5
lastOctets = $6


read -p 'Saisir un port pour lacces exterieur: ' port

#Création de la vm sur le proxmox
ssh root@172.31.10.1 vzctl create "$lastOctets" --ostemplate debian-7.0-standard_7.0-2_i386 #création du conteneur avec un numéro et choix du template
ssh root@172.31.10.1 vzctl set "$lastOctets" --onboot yes --ipadd "$lastOctets" --nameserver 172.31.1.1 --hostname "$nomMachine".reseau-labo.fr --searchdomain reseau-labo.fr --cpus 1 --ram 512M --swap 512M --diskspace 20G --save #configuration du conteneur
ssh root@172.31.10.1 vzctl set "$lastOctets" --userpasswd root:"$mdp" --save #création du compte utilisateur avec mdp
ssh root@172.31.10.1 vzctl start "$lastOctets" #demarage du conteneur
echo "La machine $nomMachine a ete cree avec succee"

#Connexion en ssh au serveur DNS 172.31.1.1
#Ajout au DNS DMZ
ssh root@172.31.1.1 echo "$nomMachine	IN	CNAME	labo.wha.la." ' >> /etc/bind/db/db.reseau-labo.fr' #recuperation du nom du conteneur
ssh root@172.31.1.1 echo "$lastOctets  IN	PTR	$nomMachine.reseau-labo.fr." ' >> /etc/bind/db/db.31.172.in-addr.arpa' #recuperation du nom du conteneur ainsi que la fin de l'adresse ip
ssh root@172.31.1.1 /etc/bind/scripts/incrSerial.sh #Lancement du script pour incrementer serial
ssh root@172.31.1.1 /etc/init.d/bind9 restart #redemarage du service bind

echo "La machine $nomMachine a ete ajouter au serveur DNS"

#Creation de la regle Nat
ssh root@172.31.0.1 "iptables -t nat -A PREROUTING -i eth0 -p tcp --dport 22222 -j DNAT --to-destination $ip:22"  #recuperation de l'adresse ip de la machine

#Creation virtualhost
ssh root@172.31.0.1 "(echo '<VirtualHost *:80>'
echo ServerName $nomMachine.reseau-labo.fr 
echo ProxyPreserveHost On
echo ProxyRequests On
echo ProxyPass / http://$ip/
echo ProxyPassReverse / http://$ip/
echo '</VirtualHost>')" " > /etc/apache2/sites-available/$nomMachine.reseau-labo.fr"  #recuperation du nom du conteneur et son adresse ip 

echo "la virtuahost a ete cree avec succee"

ssh root@172.31.0.1 "a2ensite $nomMachine.reseau-labo.fr" #activation du virtualhost
ssh root@172.31.0.1 service apache2 reload #redemarage du service apache2

echo "Le serveur apache a ete redemarrer avec succee"
echo "la virtuahost a ete cree avec succee"