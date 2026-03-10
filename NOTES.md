# NOTES.md

## 1. Wat ik heb gebouwd

Een Catalog Health Dashboard in Laravel + Filament waarmee je een productfeed kunt inladen, normaliseren, analyseren en inzichtelijk maken.

**Onderdelen:**
- `catalog:import` artisan-commando: laadt de JSON-fixture in, mapt naar interne structuur en berekent kwaliteitsscore
- 10 issue-regels (8 uit de opdracht + 2 eigen): elk als losse klasse in `app/Catalog/Analysis/Rules/`
- Filament-resource met statistieken-widget, gefilterde tabel en product-detailpagina
- `readiness_score` (0–100) per product als KPI voor beslissers

---

## 2. Belangrijkste keuzes en trade-offs

**Mapstructuur `app/Catalog/`**
Logica staat volledig los van Filament. De UI-laag (`Filament/`) doet niets behalve weergeven.
Dit maakt regels makkelijk testbaar en uitbreidbaar zonder Filament aan te raken.

**Één klasse per regel**
Elke issue-regel is een aparte klasse die `IssueRule` implementeert. Nieuwe regel toevoegen = nieuw bestand aanmaken, niets aanpassen in bestaande code (Open/Closed principe).

**`raw_payload` JSON-kolom**
In plaats van 25+ kolommen te modelleren, bewaar ik de volledige brondata in één JSON-kolom.
Ik selecteer alleen de velden die ik écht nodig heb. Dit voorkomt over-engineering en houdt de migratie eenvoudig.

**Lokale fixture als standaard**
De `CatalogFeedClient` kijkt eerst of de lokale JSON-fixture bestaat. Zo werkt de demo altijd zonder netwerkverbinding.

**Readiness score**
Eigen toevoeging. Begin op 100, trek punten af per ernst (Hoog: −20, Midden: −10, Laag: −5).
Geeft een collega één getal om snel te prioriteren — geen tabel vol groene/rode nee/ja-vinkjes hoeven scannen.

---

## 3. Wat ik bewust niet heb gebouwd

- **Auth en rollen**: niet nodig voor een interne tool op demoschaal
- **Meerdere feeds combineren**: buiten scope
- **Tests**: geen tijd voor; zou als eerst `CatalogHealthAnalyzerTest` schrijven
- **Artisan scheduler voor automatisch re-importeren**: bewust weggelaten

---

## 4. AI-gebruik

**Gebruikte tools:** GitHub Copilot (Chat) - Claude Sonnet 4.6

**Een prompt die echt geholpen heeft:**
In mijn CatalogHealthStats-widget toont de 'Kritieke problemen'-stat altijd 0, terwijl ik weet dat producten met severity: high in de database staan. De query is CatalogItem::where('issues', 'like', '%"severity":"high"%'). Pas dit aan zodat de juiste data getoond wordt

**AI-output die ik heb aangepast of verworpen:**
Copilot stelde voor om de issue-analyse direct in de Filament-resource te doen via `mutateFormDataBeforeCreate`. Dat heb ik bewust verworpen — businesslogica hoort niet in de UI-laag. In plaats daarvan zit de logica in `CatalogHealthAnalyzer` en de losse `IssueRule`-klassen.

**Eigen keuzes:**
- De `readiness_score`-berekening en het `LowReviewCountRule` zijn volledig van mij
- `SpamTitleRule`: detecteert titels met spam-woorden ("cheap", "best", "free", etc.) of >50% hoofdletters — komt vaak voor in slechte feeds en is slecht voor SEO
- De keuze voor `raw_payload` als JSON-kolom (AI stelde een volledig model voor)
- De `standaard()`-factory op de Analyzer (AI had dit verspreid over de seeder)

---

## 5. Als ik nog 2 uur had

1. **Unit tests voor elke IssueRule** — simpele PHPUnit tests, hoge waarde
2. **Artisan-scheduler**: `catalog:import` elke nacht draaien
3. **AI-readiness badge**: label producten die volledig klaar zijn voor AI-verrijking (score 100 + omschrijving > 150 tekens + review_count > 10)
4. **Export**: CSV-download van alle producten met problemen
5. **Drill-down per issue-type**: filter "toon alle producten met ontbrekende afbeelding"
