# WTECH24 Semestrálny projekt
- [WTECH24 Semestrálny projekt](#wtech24-semestrálny-projekt)
  - [Zadanie](#zadanie)
  - [Fyzický dátový model (upravený)](#fyzický-dátový-model-upravený)
  - [Návrhové rozhodnutia](#návrhové-rozhodnutia)
    - [Pridané knižnice a rozšírenia](#pridané-knižnice-a-rozšírenia)
    - [Používatelia](#používatelia)
    - [Objednávky a košík](#objednávky-a-košík)
  - [Programové prostredie](#programové-prostredie)
  - [Implementácia](#implementácia)
    - [Zmena množstva produktov](#zmena-množstva-produktov)
    - [Prihlásenie](#prihlásenie)
    - [Vyhľadávanie](#vyhľadávanie)
    - [Pridanie produktu do košíka](#pridanie-produktu-do-košíka)
    - [Stránkovanie](#stránkovanie)
    - [Filtrovanie](#filtrovanie)
  - [Záznamy obrazoviek](#záznamy-obrazoviek)

## Zadanie
Cieľom nášho projekt je vytvoriť funkčnú simuláciu e-shopu s ľubovoľnou selekciou produktov, ktorý daný e-shop poskytuje. V našej implementácií sme si zvolili predaj mobilných telefón a iných produktov v oblasti mobilných technológií. Našou úlohou je napísať aplikovateľné princípy frontend a backend funkcií, ktoré budú nosnou časťou nášho projektu. 


## DDL
Program obsahuje migračné súbory potrebné pre vytvorenie databázy, databázu vytvoríme spustením príkazu `php artisan migrate:fresh --seed`, pričom sa aj nagenerujú dáta do tabuliek. 
## Návrhové rozhodnutia
Pri navrhovaní systém sme sa rozhodli orientovať sa plne na server-side riešenie, teda chceli sme sa vyhnúť odkladania údajov do úložísk koncových používateľov - rovnako, tak na úkor dátovej záťaže sme nadobudli jednoduchšiu implementáciu. Toto naše rozhodnutie najviac ovplyvnilo manažovanie používateľských účtov a ich ukladanie produktov do virtuálneho nákupného košu.
### Pridané knižnice a rozšírenia
Pre všetky funkcionality sme nepridávali žiadne špecifické knižnice, ktoré by neboli súčasťou základnej stavby Laravelu, avšak v rámci našej databázy sme pridali rozšírenie s názvom - **PG_TRGM**. Toto rozšírenie za nás rieši problematiku vyhľadávania najpríbuznejšieho výstupnému reťazcu znakov na základe špecifického vstupu. Čo patrične zjednodušuje implementáciu vyhľadávania, kde túto funkciu využívame.
### Používatelia
Náš systém rozpoznáva 3 typy používateľov: **registrovaného, neregistrovaného a adamina**. Z tabuľky nižšie vidíme aké základné funkcie aká rola nadobúda. Ako je vyššie avizované všetky dáta, ktoré udržujeme podľa fyzického modelu sú uložené iba v databáze a nie lokálne u jednotlivých používateľoch.

| |Objednávať|Prezerať objednávky|Svojvoľné odhlásenie|Editovať produkty|
|---|---|---|---|---|
| **Neregistrovaný** | ✅ | ❌ | ❌ | ❌ |
| **Registrovaný** | ✅ | ✅ | ✅ | ❌ |
| **Admin** |✅ | ✅ | ✅ | ✅ |

- **Admin** - ide o registrovaného usera, ktorý sa zapíše do systému pomocou nášho registračného formuláru a následne je jeho záznam modifikovaný v stĺpci `admin` na hodnotu true. Túto špecifickú zmenu nevieme vykonať priamo z nášho e-shopu, teda je potrebné, aby správca systému vykonal túto zmenu priamo nad databázou. 
- **Registrovaný** - Registrovaný používateľ vzniká v našom systéme po registrácií cez náš registračný formulár. Tento používateľ má prístup k svojim objednávkam cez profil a všetky jeho údaje sú uchované na dobu neurčitú.
- **Neregistrovaný** - Neregistrovaný používateľ vzniká pri prvom vložení produktu do virtuálneho košu. Vzniká v databáze bez vyplnených údajov a má pridelený jediný údaj `temporary`, ktorí ho špecifikuje. Životnosť nášho dočasného používateľa je ukončená vypršaním časového limitu, kedy sa skontrolujú v databáze všetci používatelia, ktorý majú temporary hodnotu ako true a ak čas od vytvorenia prekročí daný limit, tak sú vymazaný z databázy, avšak ich objednávky sú zachované. Tento úkon by sa dal vykonávať chron-jobom, avšak pre jednoduchosť implementácie mi vykonávame túto kontrolu pri každom štandardnom odhlásení/vypršaní laravel session. 

### Objednávky a košík
Náš systém dovoľuje používateľom mať evidovaných viacero objednávok, avšak v rámci jedného používateľa v jednom čase dovoľuje iba jeden košík. Vytvárame vždy novú inštanciu košíka pri ukončenej predošlej objednávke alebo pri prvotnej interakcií s nákupnými funkciami nášho e-shopu. Pri zhotovení objednávky zaregistrovaným či nezaregistrovaným používateľom vytvárame novú inštanciu celkovej objednávky - zaregistrovaný používatelia nadobúdajú možnosť otvorenia svojho profilu, kde môžu vidieť detail svojich objednávok. Toto zabezpečujeme pomocou funkcií Gates v Laraveli a určujeme rolu pomocou hodnoty zápisu v stĺpci `temporary`, ktorý označuje dočasných (neregistrovaných) používateľov. Ako je aj vyššie písané všetky dáta sú ukladané zo strany systému a používateľ neukladá žiadne dodatočné dáta.

## Programové prostredie
Na celú realizáciu nášho projektu sme použili Laravel framework, na štýlovanie front-endu sme použili Tailwind a celá aplikácie operuje nad Postgresql databázou.

## Implementácia
### Zmena množstva produktov
Vychádzajúc z nášho fyzického modelu, my definujeme množstvo daného produktu v "objekte" cart_item. Teda pri kliknutí tlačidiel na zmenu počtu produktov sa jednoduchým API volaním zmení a aktualizujú sa prepočty novej výslednej ceny. V prípade, že daný produkt bude mať počet kusov rovný 0 celá položka zoznamu je následne odstránená. 
### Prihlásenie
Po odoslaní prihlasovacieho formuláru prebehne validácia vstupov, ktorá zabezpečí, že na vstup do autorizačnej funkcie prídu 2 reťazce znakov, ktoré môžeme otestovať s našou databázou. Na základe login-u môžeme vybrať daný záznam z databázy, zašifrovať vstupné heslo a porovnať ho s údajom z databazy. Pri úspešnom nájdení a overení, je daný user autorizovaný - v prípade, že ho nenájdeme alebo používateľ zadal zlé heslo, informujeme koncového používateľa o neúspešnej autorizácií.

### Vyhľadávanie
Naša implementácia spočíva v zobrazovaní modálneho okna s výsledkami daného vyhľadávania. Do vyhľadávacieho okna používateľ vloží reťazec znakov, ktorý symbolizuje hľadaný produkt, následne sa cez daný vstup pri zmene posiela dopyt na endpoint, ktorý prijíma reťazec znakov a hľadá nad tabuľkou produktov najväčšiu zhodu medzi menom produktu a zadaným reťazcom znakov. Na toto porovnávanie používame rozšírenie PG_TRGM a funkciu word_similarity(), ktorá v číselnej hodnote vyjadrí zhodu dvoch reťazcov znakov, následne výsledky zoradíme od najvyššej tieto hodnoty po najnižšiu a zobrazíme ich v danom okne.

### Pridanie produktu do košíka
Koncový používateľ má možnosť pridať nový produkt do košíka cez tlačidlo "Kúpiť" pri zobrazovaní všetkých produktoch vyhľadávania v obchode. Druhý spôsob je vybrať špecifické množstvo pri "single-page" zobrazení produktu. Následne sa po potvrdení skontroluje či daný používateľ už nemá otvorenú shopping-session, ak nie vytvorí sa mu nová a taktiež sa vytvorí aj nový záznam v tabuľke "shopping_sessions" a aj v tabuľke "cart_items". V prípade, že už má vytvorený košík pridá sa nový "cart_items" záznam s novým produktom a aktualizuje sa daná shopping session. Následne si načítavame všetky údaje o otvorenej inštancií košíka a všetkých vložených produktov, ktoré si môže používateľ pozerať a poprípade aj meniť. 
 
### Stránkovanie
Na vytvorenie funkcie stránkovania používame základnú funkciu Larvel-u, ktorá umožňuje pri dopyte do databázy automaticky pripraviť zhluky n-tíc chcených objektov/dát na zobrazenie a následne vygeneruje základný komponent, ktorý je predštýlovaný a pripravený na funkčné použitie. Na aplikovanie vlastného štýlovania sme pomocou príkazu vytvorili vlastnú inštanciu tohto komponentu a nastavili dané štýlovanie podľa vlastnej potreby. Vytváranie jednotlivých linkov a preklikov v rámci stránkovania je manažované daným framework-om.

### Filtrovanie
Náš systém povoľuje filtrovanie na ľubovoľnom počte používaných parametrov, následne ma fixne danú možnosť nastavenia maximálnej ceny vyhľadávania. Pri načítaní daného filtrovacieho rozhrania načítame všetky používané parametere a zahrnieme ich do elementu formuláru pod elementom select. Následne si používateľ môže z každého parametru vybrať špecifickú kombináciu výberu. Pri filtrovaní sa všetky tieto podmienky navzájom logicky viažu pomocou spojky "and". Následne dané kritéria sú spracované a z databázy produktov vyberieme iba tie, ktoré spĺňajú všetky kritéria - následne sú spracované a zobrazené na stránke obchodu.
Filtrovanie používame na 2 stránkach:
- **Obchod:**  filtrovanie na základe ceny, atribútov a zoradenie zostupne/vzostupne
- **Admin zóna:** zjednodušený typ filtra na základe mena,id a typu pruduktu.  


