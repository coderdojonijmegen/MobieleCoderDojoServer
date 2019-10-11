Hardware vereisten
==================

* Krachtig genoeg om de genoemde software en 2 docker containers te kunnen draaien.
* Compact, zodat het makkelijk mee te nemen is naar de bibliotheek locaties.
* Robuust (geen bewegende onderdelen), zodat het tegen een stootje kan.
* Bij voorkeur geïntegreerde voeding.
* 250GB - 500GB SSD, bij voorkeur 500GB om meerdere Docker images te kunnen bevatten
* 16GB RAM
* Core i5 of vergelijkbaar

CoderDojo Nijmegen heeft gekozen voor de volgende hardware (mei 2019):

============   =================================
Onderdeel      Merk en type
============   =================================
Barebone       `Intel NUC Kit NUC8i5BEK <https://tweakers.net/pricewatch/1239721/intel-nuc-kit-nuc8i5bek.html>`_
Werkgeheugen   `G.Skill Ripjaws F4-2400C16S-16GRS <https://tweakers.net/pricewatch/548343/g-punt-skill-ripjaws-f4-2400c16s-16grs.html>`_
Opslag         `Samsung 970 Evo Plus 500GB <https://tweakers.net/pricewatch/1303746/samsung-970-evo-plus-500gb.html>`_
============   =================================

Update oktober 2019
-------------------

Het originele plan was om alleen de NUC te gebruiken, zowel als WIFI client en access-point. Dit werkt ook mooi, maar
helaas tot zo'n 4 verbonden computers. Daarboven crashed de WIFI driver en moet de machine herstart worden om weer
te laten werken. Uiteraard is dit veel te weinig voor een CoderDojo en daarmee niet werkbaar.

Vandaar dat we ervoor hebben gekozen om een oude router te pakken en deze in bridge-mode te zetten. Hierdoor werkt deze
als access-point en hoeft de NUC alleen als router te werken. Helaas hiermee wel wat minder draagbaar, maar nog altijd
goed bruikbaar.

Het installatiescript bevat de functionaliteit voor stand-alone gebruik van de NUC nog altijd, maar is aangepast voor
gebruik met de externe access-point.