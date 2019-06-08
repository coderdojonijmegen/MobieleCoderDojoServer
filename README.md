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
 2. Client verbinden: _nmcli device wifi connect "&lt;ssid publieke wifi>"_ indien er een WPA2 beveiliging gebruikt wordt, is het commando _nmcli device wifi connect "&lt;ssid wpa2 wifi>" password "&lt;wpa2 wachtwoord>"_
