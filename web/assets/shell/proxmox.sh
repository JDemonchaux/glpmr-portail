#!/bin/bash


ssh root@10.0.0.192 vzctl create 102 --ostemplate $1 
ssh root@10.0.0.192 vzctl set 102 --onboot yes --ipadd $2 --nameserver 172.31.10.1 --hostname $3 --cpus 1 --ram 512M --swap 512M --diskspace 20G --save
ssh root@10.0.0.192 vzctl set 102 --userpasswd root:$4 --save
ssh root@10.0.0.192 vzctl start 102
