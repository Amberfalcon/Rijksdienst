# Rijksdienst

Rijksdienst is een informatieplatform in ontwikkeling dat overheidsinformatie overzichtelijk en met elkaar verbonden toegankelijk wil maken.

Het platform is bedoeld om informatie over onder meer overheidsorganisaties, beleid, officiële documenten, statistieken, bronnen, geschiedenis en onderlinge relaties op één plek vindbaar te maken.

> **Let op:** dit is een onafhankelijk project in ontwikkeling en geen officiële website van de Nederlandse overheid. De informatie op de eerste startpagina is demonstratie-inhoud en geen geverifieerde overheidsinformatie.

## Eerste frontend

De eerste interface bevat:
- Een responsieve startpagina in het Nederlands.
- Een zoekervaring die werkt op lokale voorbeeldgegevens.
- Zes ingangen naar verschillende soorten overheidsinformatie.
- Uitgelichte themablokken die laten zien hoe beleid, documenten en cijfers kunnen samenhangen.
- Expliciete aanduidingen voor voorbeeldinhoud.
- Een mobiel navigatiemenu en toetsenbordfocus-stijlen.

## Lokaal starten

Benodigd: een recente Node.js LTS-versie en npm.

```powershell
npm.cmd install
npm.cmd run dev
```

Open daarna de lokale URL die Vite in de terminal toont.

Controleer de productiebuild met:

```powershell
npm.cmd run build
```

## Techniek

- Vue 3
- TypeScript
- Vite
- CSS met herbruikbare ontwerpvariabelen en responsieve componentstijlen

De startpagina gebruikt op dit moment uitsluitend statische voorbeeldgegevens. Er is nog geen backend, database of koppeling met officiële databronnen.

## Richting

1. Startpagina en basisnavigatie.
2. Zoekervaring en overzichtelijke informatiecategorieën.
3. Herbruikbare pagina's voor organisaties, documenten en beleid.
4. Relaties, tijdlijnen en bronvermelding.
5. Backend, gegevensmodel en gecontroleerde koppelingen met bronnen.
6. Tests, toegankelijkheidscontrole en documentatie.

## Ontwerpgrondslag

Zie [docs/FRONTEND_FOUNDATION.md](docs/FRONTEND_FOUNDATION.md) voor de uitgangspunten rond productvisie, UX, componenten, techniek en toegankelijkheid.
