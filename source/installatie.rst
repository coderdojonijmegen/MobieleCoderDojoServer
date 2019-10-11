###########
Installatie
###########

Bibliotheken hebben meestal publiek WIFI. Een netwerk aansluiting is zeldzamer.
Om de Ninja's zowel gebruik te kunnen laten maken van de MCS en het publieke internet,
moet de MCS verbonden worden met de publieke WIFI van de bibliotheek. Vervolgens moet deze internetverbinding
gedeeld worden met het access-point dat aan de MCS is verbonden of er op draait.

Om de installatie zo makkelijk mogelijk te maken, is het meeste werk geautomatiseerd in een script. Volg daarvoor de
volgende stappen:

 1. Verbind de machine aan het netwerk met een netwerk kabel
 2. Installeer Ubuntu Server (18.04LTS op het moment van schrijven)
 3. Voer vervolgens de volgende instructies uit:

.. code-block:: none

   git clone https://github.com/coderdojonijmegen/MobieleCoderDojoServer.git
   cd MobieleCoderDojoServer/
   sudo ./install.sh

Als het script succesvol is uitgevoerd, volgt de melding dat installatie klaar is.

 4. Verwijder dan de netwerk kabel van de machine. Dit is nodig, omdat de geïnstalleerd DHCP server anders
    conflicteert met de DHCP server in je thuis router.
 5. Herstart de computer dan met commando:

.. code-block:: none

   sudo restart now

Nadat de machine opnieuw is opgestart kun je er verbinding mee maken door:

 6. a Verbind de WAN/internet poort van de gebridgde router aan de netwerk aansluiting van de MCS en verbind vervolgens
    je laptop met het WIFI netwerk van de gebridgde router.

Of:

 6. b Verbind je laptop en de MCS direct met elkaar via een netwerk kabel.

In beide situaties krijgt je laptop een IP adres van de MCS in het adresbereik van 10.0.0.2 tot en met 10.0.0.200. De MCS
is vervolgens bereikbaar op http://cocderdojo.server of http://mcs.





WordPress
---------

Plugins
^^^^^^^

 1. Duplicate Post: (https://nl.wordpress.org/plugins/duplicate-post/)
    aangezien de structuur van onze berichtjes iedere keer zoveel mogelijk gelijk zijn, is het handig om gewoon een
    vorig bericht te copiëren. Dat kan met deze plugin.
 2. Tuxedo Big File Uploads (https://nl.wordpress.org/plugins/tuxedo-big-file-uploads/)
    standaard is de upload grootte beperkt. Maar, omdat we soms grote installatie bestanden ter beschikking willen
    stellen, is dit maximum vervelend. Met deze plugin kun je een ander maximum of onbeperkt maximum instellen.