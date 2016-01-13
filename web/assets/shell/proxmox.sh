#!/bin/bash

#Scrit de création de vm en dmz
#Connexion en ssh au serveur proxmox 172.31.10.1

#Création de la vm sur le proxmox
ssh root@172.31.10.1 vzctl create 102 --ostemplate debian-7.0-standard_7.0-2_i386 #création du conteneur avec un numéro et choix du template
ssh root@172.31.10.1 vzctl set 102 --onboot yes --ipadd 172.31.10.50 --nameserver 172.31.1.1 8.8.8.8 --hostname testYoucef.reseau-labo.fr --searchdomain reseau-labo.fr --cpus 1 --ram 512M --swap 512M --diskspace 20G --save #configuration du conteneur
ssh root@172.31.10.1 vzctl set 102 --userpasswd root:root --save
ssh root@172.31.10.1 vzctl start 102
echo "La machine a ete cree avec succee"

#Connexion en ssh au serveur DNS 172.31.1.1
#Ajout au DNS DMZ
echo 'testYoucef	IN	CNAME	labo.wha.la.' | ssh root@172.31.1.1 "cat >> /etc/bind/db/db.reseau-labo.fr"
echo '10.50	IN	PTR	testYoucef.reseau-labo.fr' | ssh root@172.31.1.1 "cat >> /etc/bind/db/db.31.172.in-addr.arpa"
ssh root@172.31.1.1 /etc/init.d/bind9 restart

echo "La machine a ete ajouter au serveur DNS"

#Creation de la regle Nat
ssh root@172.31.0.1 'iptables -t nat -A PREROUTING -i eth0 -p tcp --dport 22222 -j DNAT --to-destination 172.31.10.50:22'

#Creation virtualhost
echo '<VirtualHost *:80>
	ServerName testYoucef.reseau-labo.fr
	ProxyPreserveHost On
	ProxyRequests On
	ProxyPass / http://172.31.10.50/
	ProxyPassReverse / http://172.31.10.50/
</VirtualHost>' | ssh root@172.31.0.1 "cat > /etc/apache2/sites-available/testYoucef.reseau-labo.fr"

echo "la virtuahost a ete cree avec succee"

ssh root@172.31.0.1 a2ensite testYoucef.reseau-labo.fr
ssh root@172.31.0.1 service apache2 reload

echo "Le serveur apache a ete redemarrer avec succee"