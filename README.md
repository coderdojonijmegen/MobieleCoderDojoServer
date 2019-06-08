# Mobiele CoderDojo Server (MCS)
## WIFI delen
### Installeren
Bibliotheken hebben meestal publiek WIFI. Een netwerk aansluiting is zeldzamer.
Om de Ninja's zowel gebruik te kunnen laten maken van de MCS en het publieke internet, 
moet de MCS verbonden worden met de publieke WIFI van de bibliotheek. Vervolgens moet deze internetverbinding
gedeeld worden met het access-point dat op de MCS draait.

Om dit eenvoudig te kunnen realiseren, kan [create_ap](https://github.com/oblique/create_ap) gebruikt worden. Dit tooltje
configureert de WIFI adapter en de software routering om de client met het accesspoint te verbinden.
Om het tooltje te kunnen gebruiken, moet je de volgende stappen uitvoeren:

 * _sudo apt install git hostapd iproute2 iw haveged dnsmasq iptables procps bash util-linux build-essentials_
 * _git clone https://github.com/oblique/create_ap_
 * _cd create_ap_
 * _sudo make install_

Het tooltje is nu gereed voor gebruik.
### Verbinden
Om de client te verbinden en het accesspoint op te zetten, moeten een aantal commando's worden uitgevoerd. Hierbij is de volgorde van belang gebleken:

 1. Accesspoint opzetten: _sudo ./create_ap wlp0s20f3 wlp0s20f3 CoderDojoServer dojo2018_ (create_ap &lt;client> &lt;ap> &lt;ssid> &lt;netwerk wachtwoord>)
 2. Beschikbare netwerken bekijken: _nmcli device wifi_
 3. Client verbinden: _nmcli device wifi connect "&lt;ssid publieke wifi>"_ indien er een WPA2 beveiliging gebruikt wordt, is het commando _nmcli device wifi connect "&lt;ssid wpa2 wifi>" password "&lt;wpa2 wachtwoord>"_

## Firewall
Omdat de MCS aan publieke netwerken hangt, gebruiken we een firewall om ongeauthorizeerd inkomend verkeer zoveel mogelijk te blokkeren.
De applicaties die we toelaten zijn:

 * SSH: management toegang voor systeemonderhoud en remote access; vanaf het externe bedrade netwerk en het interne wifi netwerk
 * HTTP(S?): serveren van instructies, installers en ondersteunende services; alleen vanaf het interne wifi netwerk
 
Voor de eenvoud en omdat het access point alleen IPv4 ondersteund, blokkeren we alle IPv6 verkeer. We maken gebruik van de UFW firewall (Uncomplicated FireWall) met de volgende regels:

```
To                         Action      From
--                         ------      ----
22/tcp                     ALLOW IN    Anywhere                  
80/tcp on ap0              ALLOW IN    Anywhere (ap0 is de virtuele wifi adapter waarop het accesspoint draait)
```
In `sudo nano /etc/default/ufw` `IPV6=yes` vervangen door `IPV6=no`.
