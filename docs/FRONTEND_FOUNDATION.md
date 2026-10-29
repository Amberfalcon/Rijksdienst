# Frontend-grondslag

**Project:** Rijksdienst  
**Documentversie:** 0.1  
**Status:** Eerste uitgangspunten; aanpasbaar naarmate het project groeit.

## 1. Productvisie

Rijksdienst wil overheidsinformatie op een begrijpelijke manier vindbaar en onderling navigeerbaar maken. Gebruikers moeten organisaties, beleid, documenten, statistieken en officiële bronnen kunnen vinden en waar mogelijk verbanden daartussen kunnen volgen.

Rijksdienst is een onafhankelijk project en mag zich niet voordoen als een officiële overheidsdienst.

## 2. Ervaring en ontwerp

De interface hoort rustig, helder, betrouwbaar en functioneel te zijn. De visuele taal kan aansluiten bij gangbare Nederlandse overheidspatronen, zonder officiële logo's, keurmerken of overheidsidentiteit misleidend na te bootsen.

Uitgangspunten:
- Een duidelijke kop en primaire navigatie.
- Een prominente zoekfunctie met begrijpelijke zoekvoorbeelden.
- Logische categorieën en herkenbare informatiekaarten.
- Duidelijke bronvermelding en datums bij inhoud.
- Consistente typografie, spacing, kleurgebruik en focusstijlen.
- Responsief gedrag en bruikbaarheid met toetsenbord en schermlezer.
- Geen decoratie die de vindbaarheid of leesbaarheid belemmert.

## 3. Eerste startpagina

De eerste iteratie bevat:
1. Merknaam en korte uitleg over het platform.
2. Primaire navigatie.
3. Zoekveld voor organisaties, beleid, documenten en statistieken.
4. Informatiecategorieën.
5. Een sectie voor uitgelichte of recent toegevoegde informatie met expliciete voorbeeldlabels.
6. Een sectie voor bron- en projectinformatie.

Voorbeeldinhoud moet als demonstratie herkenbaar zijn en mag niet worden gepresenteerd als geverifieerde overheidsinformatie.

## 4. Componenten en informatievormen

Begin met kleine herbruikbare componenten, bijvoorbeeld:
- SiteHeader
- PrimaryNavigation
- SearchPanel
- CategoryCard
- InformationCard
- SourceCitation
- SiteFooter

Voeg alleen componenten toe wanneer ze hergebruik of consistentie verbeteren. Houd informatiepresentatie uitbreidbaar, zodat later organisaties, documenten, datasets en tijdlijnen dezelfde visuele basis kunnen delen.

## 5. Technische uitgangspunten

De eerste frontend gebruikt Vue 3, TypeScript en Vite. Houd componenten begrijpelijk en beperk externe dependencies totdat er een concrete behoefte bestaat.

Aanbevolen afspraken:
- Gebruik TypeScript-types voor gedeelde informatievormen.
- Scheid inhoudsdata van presentatie waar dat eenvoudiger onderhoud mogelijk maakt.
- Gebruik semantische HTML-elementen.
- Voeg duidelijke laad-, lege- en fouttoestanden toe wanneer echte data wordt aangesloten.
- Houd geheimen, tokens en persoonlijke gegevens uit de repository.
- Gebruik Git-commits voor kleine, beschrijvende stappen.

De backend- en databasekeuze blijft open totdat de eerste frontendbehoeften zijn gevalideerd. Een API kan later worden toegevoegd zonder de huidige startpagina onnodig ingewikkeld te maken.

## 6. Toegankelijkheid en kwaliteit

Werk stapsgewijs toe naar WCAG 2.2 AA. Controleer onder andere:
- Contrast en leesbaarheid.
- Zichtbare toetsenbordfocus.
- Logische koppenstructuur en labels.
- Toetsenbordbediening van navigatie en zoeken.
- Responsieve weergave op smalle schermen.
- Duidelijke foutmeldingen en feedback.

## 7. Gefaseerde roadmap

### Fase 1 — Fundament
- Startpagina en basisnavigatie.
- Eerste ontwerpvariabelen en herbruikbare componenten.
- Voorbeelddata en responsieve lay-out.

### Fase 2 — Vindbaarheid
- Zoekinteractie en resultatenlijst.
- Filters en categorieën waar nodig.
- Lege en geen-resultaten-toestanden.

### Fase 3 — Informatiepagina's
- Organisaties, documenten, beleid en statistieken.
- Bronvermelding, datums en gerelateerde informatie.

### Fase 4 — Relaties en geschiedenis
- Relaties tussen entiteiten.
- Tijdlijnen en wijzigingen met herleidbare bronnen.

### Fase 5 — Data en betrouwbaarheid
- Datamodel en backend/API.
- Bronvalidatie, actualiteitsinformatie, tests en monitoring.

## 8. Nog te beslissen

- Definitieve informatie- en zoekstructuur.
- Backend en database.
- Herkomst, licenties en actualiseringsfrequentie van gegevens.
- Visuele identiteit en toepasselijke toegankelijkheidsnormen.
- Test- en publicatieproces.

Deze keuzes worden gemaakt op basis van concrete behoeften, niet vooraf uit gewoonte.
