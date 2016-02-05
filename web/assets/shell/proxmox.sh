#!/bin/bash

#Scrit de cr�ation de vm en dmz
#Connexion en ssh au serveur proxmox 172.31.10.1



osTemplate = $1
ipAdresse = $2
hostname = $3
password = $4
id = $5
lastOctets = $6

#Création de la vm sur le proxmox
ssh root@172.31.10.1 vzctl create $id --ostemplate $osTemplate #cr�ation du conteneur avec un num�ro et choix du template
ssh root@172.31.10.1 vzctl set $id --onboot yes --ipadd $ipAdresse --nameserver 172.31.1.1 8.8.8.8 --hostname $hostname.reseau-labo.fr --searchdomain reseau-labo.fr --cpus 1 --ram 512M --swap 512M --diskspace 20G --save #configuration du conteneur
ssh root@172.31.10.1 vzctl set $id --userpasswd $hostname:$password --save
ssh root@172.31.10.1 vzctl start $id
echo "La machine a ete cree avec succee"



#Cr�ation de la vm sur le proxmox
#ssh root@172.31.10.1 vzctl create $ --ostemplate debian-7.0-standard_7.0-2_i386 #cr�ation du conteneur avec un num�ro et choix du template
#ssh root@172.31.10.1 vzctl set 102 --onboot yes --ipadd 172.31.10.50 --nameserver 172.31.1.1 8.8.8.8 --hostname testYoucef.reseau-labo.fr --searchdomain reseau-labo.fr --cpus 1 --ram 512M --swap 512M --diskspace 20G --save #configuration du conteneur
#ssh root@172.31.10.1 vzctl set 102 --userpasswd root:root --save
#ssh root@172.31.10.1 vzctl start 102
#echo "La machine a ete cree avec succee"

#Connexion en ssh au serveur DNS 172.31.1.1
#Ajout au DNS DMZ
echo '$hostname         IN	CNAME	labo.wha.la.' | ssh root@172.31.1.1 "cat >> /etc/bind/db/db.reseau-labo.fr"
echo '$lastOctets	IN	PTR	$hostname.reseau-labo.fr' | ssh root@172.31.1.1 "cat >> /etc/bind/db/db.31.172.in-addr.arpa"
ssh root@172.31.1.1 /etc/init.d/bind9 restart

echo "La machine a ete ajouter au serveur DNS"

#Creation de la regle Nat
ssh root@172.31.0.1 'iptables -t nat -A PREROUTING -i eth0 -p tcp --dport 22222 -j DNAT --to-destination $ipAdresse:22'

#Creation virtualhost
echo '<VirtualHost *:80>
	ServerName $hostname.reseau-labo.fr
	ProxyPreserveHost On
	ProxyRequests On
	ProxyPass / http://$ipAdresse/
	ProxyPassReverse / http://$ipAdresse/
</VirtualHost>' | ssh root@172.31.0.1 "cat > /etc/apache2/sites-available/$hostname.reseau-labo.fr"

echo "la virtuahost a ete cree avec succee"

ssh root@172.31.0.1 a2ensite $hostname.reseau-labo.fr
ssh root@172.31.0.1 service apache2 reload

echo "Le serveur apache a ete redemarrer avec succee"