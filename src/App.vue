<script setup lang="ts">
import { computed, ref } from 'vue'

type Entry = {
  title: string
  type: string
  description: string
  updated: string
  tags: string[]
}

const searchQuery = ref('')
const searchSubmitted = ref(false)
const mobileMenuOpen = ref(false)

const categories = [
  { number: '01', title: 'Overheidsorganisaties', description: 'Ministeries, uitvoeringsorganisaties en publieke instellingen', count: 'organisatie', icon: 'building' },
  { number: '02', title: 'Wet- en regelgeving', description: 'Wetten, besluiten, regels en officiële publicaties', count: 'regelgeving', icon: 'scale' },
  { number: '03', title: 'Beleid & publicaties', description: 'Beleidsstukken, rapporten, brieven en plannen', count: 'documenten', icon: 'file' },
  { number: '04', title: 'Cijfers & statistieken', description: 'Data, indicatoren en onderzoek in context', count: 'statistiek', icon: 'chart' },
  { number: '05', title: 'Besluiten & parlement', description: 'Kamerstukken, besluiten en bestuurlijke dossiers', count: 'parlement', icon: 'landmark' },
  { number: '06', title: 'Tijdlijnen & relaties', description: 'Ontdek hoe onderwerpen, organisaties en beleid samenhangen', count: 'beleid', icon: 'network' },
]

const entries: Entry[] = [
  {
    title: 'Ministerie van Algemene Zaken',
    type: 'Organisatie',
    description: 'Overheidsorganisatie · voorbeeldbeschrijving van taken, geschiedenis en gerelateerde onderwerpen.',
    updated: 'Voorbeeldrecord',
    tags: ['organisatie', 'ministerie', 'bestuur'],
  },
  {
    title: 'Rijksbegroting en verantwoordingsinformatie',
    type: 'Dossier',
    description: 'Een voorbeeld van hoe beleid, begrotingsstukken en verantwoordingsdocumenten bij elkaar komen.',
    updated: 'Voorbeeldrecord',
    tags: ['beleid', 'begroting', 'documenten', 'financiën'],
  },
  {
    title: 'Wet open overheid (Woo)',
    type: 'Wet- en regelgeving',
    description: 'Voorbeeldpagina voor wetgeving, publicatiegegevens, toelichting en verbonden organisaties.',
    updated: 'Voorbeeldrecord',
    tags: ['wet', 'regelgeving', 'openbaarheid'],
  },
  {
    title: 'Bevolking en demografische ontwikkelingen',
    type: 'Statistiek',
    description: 'Voorbeeld van een gegevenspagina met indicatoren, definities, bronvermelding en tijdreeks.',
    updated: 'Voorbeeldrecord',
    tags: ['cijfers', 'statistiek', 'bevolking', 'data'],
  },
  {
    title: 'Klimaatbeleid en energietransitie',
    type: 'Beleidsthema',
    description: 'Een voorbeeld van een themadossier met beleid, organisaties, rapporten en cijfers in samenhang.',
    updated: 'Voorbeeldrecord',
    tags: ['klimaat', 'energie', 'beleid', 'duurzaamheid'],
  },
  {
    title: 'Parlementaire documenten',
    type: 'Documenten',
    description: 'Voorbeeld van een verzameling stukken met documenttype, datum, herkomst en gerelateerde dossiers.',
    updated: 'Voorbeeldrecord',
    tags: ['kamerstukken', 'parlement', 'documenten', 'besluiten'],
  },
]

const filteredEntries = computed(() => {
  const query = searchQuery.value.trim().toLocaleLowerCase('nl')
  if (!query) return []
  return entries.filter((entry) =>
    [entry.title, entry.type, entry.description, ...entry.tags].some((value) =>
      value.toLocaleLowerCase('nl').includes(query),
    ),
  )
})

function submitSearch() {
  searchSubmitted.value = true
  mobileMenuOpen.value = false
}

function chooseCategory(category: string) {
  searchQuery.value = category
  searchSubmitted.value = true
  window.setTimeout(() => document.getElementById('zoekresultaten')?.scrollIntoView({ behavior: 'smooth', block: 'start' }), 40)
}

function clearSearch() {
  searchQuery.value = ''
  searchSubmitted.value = false
}
</script>

<template>
  <div class="site-shell">
    <div class="topline">
      <div class="container topline-inner">
        <span class="topline-label"><span class="status-dot"></span> Onafhankelijk informatieplatform in ontwikkeling</span>
        <a href="#over-rijksdienst" class="topline-link">Over dit project <span aria-hidden="true">↗</span></a>
      </div>
    </div>

    <header class="site-header">
      <div class="container header-inner">
        <a class="brand" href="#" aria-label="Rijksdienst startpagina">
          <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
          <span class="brand-copy">
            <span class="brand-name">rijksdienst<span class="brand-period">.</span></span>
            <span class="brand-subtitle">Overheidsinformatie verbonden</span>
          </span>
        </a>

        <button class="menu-toggle" type="button" :aria-expanded="mobileMenuOpen" aria-controls="primary-navigation" @click="mobileMenuOpen = !mobileMenuOpen">
          <span>{{ mobileMenuOpen ? 'Sluiten' : 'Menu' }}</span>
          <span class="menu-bars" aria-hidden="true"><i></i><i></i></span>
        </button>

        <nav id="primary-navigation" class="primary-nav" :class="{ 'is-open': mobileMenuOpen }" aria-label="Hoofdnavigatie">
          <a class="nav-link active" href="#ontdekken" @click="mobileMenuOpen = false">Ontdekken</a>
          <a class="nav-link" href="#categorieen" @click="mobileMenuOpen = false">Onderwerpen</a>
          <a class="nav-link" href="#uitgelicht" @click="mobileMenuOpen = false">Uitgelicht</a>
          <a class="nav-about" href="#over-rijksdienst" @click="mobileMenuOpen = false">Over Rijksdienst <span aria-hidden="true">↗</span></a>
        </nav>
      </div>
    </header>

    <main>
      <section id="ontdekken" class="hero">
        <div class="hero-grid-pattern" aria-hidden="true"></div>
        <div class="container hero-layout">
          <div class="hero-content">
            <div class="eyebrow hero-eyebrow"><span class="eyebrow-line"></span> DE WEG WIJZEN IN DE OVERHEID</div>
            <h1>De overheid,<br /><span>in samenhang.</span></h1>
            <p class="hero-intro">Vind overheidsorganisaties, beleid, documenten en cijfers. Niet als losse informatie, maar met de verbanden ertussen.</p>

            <form class="search-form" role="search" @submit.prevent="submitSearch">
              <label class="sr-only" for="main-search">Zoek in overheidsinformatie</label>
              <svg class="search-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.8" /><path d="m16 16 4.2 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" /></svg>
              <input id="main-search" v-model="searchQuery" type="search" placeholder="Zoek een organisatie, onderwerp of document..." autocomplete="off" @input="searchSubmitted = false" />
              <button type="submit" class="search-button">Zoeken <span aria-hidden="true">→</span></button>
            </form>
            <div class="search-hints">
              <span class="search-hints-label">Probeer bijvoorbeeld</span>
              <button type="button" @click="chooseCategory('klimaat')">klimaatbeleid</button>
              <button type="button" @click="chooseCategory('ministerie')">ministeries</button>
              <button type="button" @click="chooseCategory('statistiek')">statistiek</button>
            </div>
            <div class="hero-assurance"><span class="assurance-icon" aria-hidden="true">↳</span><span>Bronnen en context horen bij elkaar.</span></div>
          </div>

          <div class="hero-visual" aria-label="Illustratie van verbonden overheidsinformatie">
            <div class="visual-orbit orbit-one"></div>
            <div class="visual-orbit orbit-two"></div>
            <div class="visual-orbit orbit-three"></div>
            <div class="visual-center">
              <span class="center-mark"><span></span><span></span><span></span></span>
              <span class="center-name">rijksdienst</span>
              <span class="center-caption">informatie in context</span>
            </div>
            <div class="visual-node node-org"><span class="node-icon">ORG</span><span class="node-title">Organisaties</span><span class="node-sub">Wie doet wat?</span></div>
            <div class="visual-node node-policy"><span class="node-icon">BE</span><span class="node-title">Beleid</span><span class="node-sub">Wat is het plan?</span></div>
            <div class="visual-node node-data"><span class="node-icon">#</span><span class="node-title">Data & cijfers</span><span class="node-sub">Wat laten cijfers zien?</span></div>
            <div class="visual-node node-docs"><span class="node-icon">DOC</span><span class="node-title">Documenten</span><span class="node-sub">Wat is vastgelegd?</span></div>
            <div class="visual-note"><span class="visual-note-dot"></span> Van bron naar verband</div>
          </div>
        </div>
        <div class="hero-bottomline"><div class="container hero-bottom-inner"><span>ÉÉN VERTREKPUNT VOOR OVERHEIDSINFORMATIE</span><span class="hero-bottom-right">ONTDEK · VERGELIJK · BEGRIJP <span aria-hidden="true">↘</span></span></div></div>
      </section>

      <section v-if="searchSubmitted" id="zoekresultaten" class="search-results-section">
        <div class="container">
          <div class="results-heading">
            <div>
              <div class="eyebrow"><span class="eyebrow-line"></span> ZOEKEN</div>
              <h2>Resultaten voor <span>“{{ searchQuery.trim() }}”</span></h2>
              <p class="section-description">Demonstratieresultaten uit lokale voorbeeldgegevens.</p>
            </div>
            <button class="text-action" type="button" @click="clearSearch">Zoekopdracht wissen <span aria-hidden="true">×</span></button>
          </div>
          <div v-if="filteredEntries.length" class="results-list">
            <article v-for="entry in filteredEntries" :key="entry.title" class="result-card">
              <div class="result-type">{{ entry.type }}</div>
              <div class="result-main"><h3>{{ entry.title }}</h3><p>{{ entry.description }}</p><span class="result-date">{{ entry.updated }} · Niet-officiële voorbeeldinhoud</span></div>
              <span class="result-arrow" aria-hidden="true">↗</span>
            </article>
          </div>
          <div v-else class="empty-state">
            <span class="empty-icon" aria-hidden="true">⌕</span>
            <div><h3>Geen voorbeeldresultaten gevonden</h3><p>Probeer een ander begrip, zoals ‘beleid’, ‘ministerie’, ‘klimaat’ of ‘statistiek’.</p></div>
          </div>
        </div>
      </section>

      <section id="categorieen" class="categories-section section-pad">
        <div class="container">
          <div class="section-heading">
            <div>
              <div class="eyebrow"><span class="eyebrow-line"></span> HET LANDSCHAP VERKENNEN</div>
              <h2>Waar ben je naar op zoek?</h2>
              <p class="section-description">Begin bij een onderwerp. Volg daarna de informatie die ermee verbonden is.</p>
            </div>
            <span class="section-index">01 <span>/</span> 06</span>
          </div>
          <div class="category-grid">
            <button v-for="category in categories" :key="category.number" class="category-card" type="button" @click="chooseCategory(category.count)">
              <div class="category-card-top"><span class="category-number">{{ category.number }}</span><span class="category-icon" aria-hidden="true">
                <svg v-if="category.icon === 'building'" viewBox="0 0 24 24" fill="none"><path d="M3 20h18M5 20V8l7-4 7 4v12M9 20v-5h6v5M8 10h.01M12 10h.01M16 10h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <svg v-else-if="category.icon === 'scale'" viewBox="0 0 24 24" fill="none"><path d="M12 3v18M7 6h10M5 21h14M7 6l-4 7h8L7 6ZM17 6l-4 7h8l-4-7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <svg v-else-if="category.icon === 'file'" viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 3v6h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                <svg v-else-if="category.icon === 'chart'" viewBox="0 0 24 24" fill="none"><path d="M4 20V4M4 20h17M8 16l4-5 3 2 5-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="20" cy="6" r="1" fill="currentColor"/></svg>
                <svg v-else-if="category.icon === 'landmark'" viewBox="0 0 24 24" fill="none"><path d="m3 9 9-5 9 5M4 20h16M6 10v7M10 10v7M14 10v7M18 10v7M3 20v-2h18v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <svg v-else viewBox="0 0 24 24" fill="none"><circle cx="12" cy="5" r="2.2" stroke="currentColor" stroke-width="1.5"/><circle cx="5" cy="18" r="2.2" stroke="currentColor" stroke-width="1.5"/><circle cx="19" cy="18" r="2.2" stroke="currentColor" stroke-width="1.5"/><path d="m10.8 7-4.5 9M13.2 7l4.5 9M7.2 18h9.6" stroke="currentColor" stroke-width="1.5"/></svg>
              </span></div>
              <h3>{{ category.title }}</h3>
              <p>{{ category.description }}</p>
              <span class="category-link">Verkennen <span aria-hidden="true">↗</span></span>
            </button>
          </div>
        </div>
      </section>

      <section id="uitgelicht" class="featured-section section-pad">
        <div class="container">
          <div class="section-heading">
            <div>
              <div class="eyebrow"><span class="eyebrow-line"></span> VAN LOSSE INFORMATIE NAAR INZICHT</div>
              <h2>Zie het grotere geheel.</h2>
              <p class="section-description">Een eerste indruk van hoe informatie op Rijksdienst met elkaar kan worden verbonden.</p>
            </div>
            <span class="demo-label"><span class="demo-dot"></span> INTERACTIEF VOORBEELD</span>
          </div>
          <div class="featured-layout">
            <article class="featured-story">
              <div class="story-top"><span class="story-kicker">THEMA IN BEELD</span><span class="story-number">01 — 03</span></div>
              <div class="story-art" aria-hidden="true">
                <div class="story-sun"></div><div class="story-horizon"></div><div class="story-tower tower-one"></div><div class="story-tower tower-two"></div><div class="story-tower tower-three"></div><div class="story-ripple ripple-one"></div><div class="story-ripple ripple-two"></div>
                <span class="story-art-label">BELEID <i>×</i> DATA <i>×</i> SAMENLEVING</span>
              </div>
              <div class="story-content">
                <span class="content-type">VOORBEELDTHEMA</span>
                <h3>Klimaat en energie</h3>
                <p>Verken hoe een thema terugkomt in beleid, rapporten, organisaties en cijfers.</p>
                <button class="inline-link" type="button" @click="chooseCategory('klimaat')">Bekijk de samenhang <span aria-hidden="true">→</span></button>
              </div>
            </article>
            <div class="featured-side">
              <article class="side-story">
                <div class="side-story-icon icon-blue" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M4 20V4M4 20h16M7 15l4-4 3 2 5-7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <div class="side-story-body"><span class="content-type">CIJFERS & STATISTIEKEN</span><h3>Cijfers met context</h3><p>Een getal krijgt betekenis met een definitie, bron en tijdlijn.</p><button class="inline-link" type="button" @click="chooseCategory('statistiek')">Ontdek data <span aria-hidden="true">→</span></button></div>
                <span class="side-number">02</span>
              </article>
              <article class="side-story">
                <div class="side-story-icon icon-orange" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14 3v6h5M9 13h6M9 17h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></div>
                <div class="side-story-body"><span class="content-type">DOCUMENTEN & BRONNEN</span><h3>Van document naar dossier</h3><p>Bekijk publicaties in relatie tot beleid, onderwerpen en organisaties.</p><button class="inline-link" type="button" @click="chooseCategory('documenten')">Bekijk documenten <span aria-hidden="true">→</span></button></div>
                <span class="side-number">03</span>
              </article>
              <div class="source-note"><span class="source-note-mark">i</span><p>De inhoud op deze pagina is voorbeelddata. Er zijn nog geen officiële databronnen gekoppeld.</p></div>
            </div>
          </div>
        </div>
      </section>

      <section id="over-rijksdienst" class="about-section">
        <div class="container about-layout">
          <div class="about-stamp" aria-hidden="true"><span>RD</span><i></i><i></i><i></i></div>
          <div class="about-copy">
            <div class="eyebrow"><span class="eyebrow-line"></span> OVER HET PLATFORM</div>
            <h2>Overheid begrijpelijker maken begint met <span>verbanden leggen.</span></h2>
            <p>Rijksdienst is een onafhankelijk initiatief in ontwikkeling. Het brengt informatie uit verschillende hoeken samen, zodat je makkelijker kunt ontdekken hoe organisaties, beleid, documenten en data elkaar raken.</p>
            <a class="about-link" href="https://github.com/Amberfalcon/Rijksdienst" target="_blank" rel="noreferrer">Volg de ontwikkeling op GitHub <span aria-hidden="true">↗</span></a>
          </div>
          <div class="about-values">
            <div><span class="value-index">01</span><span><strong>Vindbaar</strong><small>Begin bij je vraag</small></span></div>
            <div><span class="value-index">02</span><span><strong>Herleidbaar</strong><small>Bronnen inzichtelijk</small></span></div>
            <div><span class="value-index">03</span><span><strong>Verbonden</strong><small>Informatie in context</small></span></div>
          </div>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <div class="container footer-main">
        <a class="brand footer-brand" href="#" aria-label="Rijksdienst startpagina">
          <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
          <span class="brand-copy"><span class="brand-name">rijksdienst<span class="brand-period">.</span></span><span class="brand-subtitle">Overheidsinformatie verbonden</span></span>
        </a>
        <p>Een onafhankelijk informatieplatform in ontwikkeling.<br />Niet verbonden aan de Nederlandse overheid.</p>
        <a class="footer-top-link" href="#ontdekken">Terug naar boven <span aria-hidden="true">↑</span></a>
      </div>
      <div class="container footer-bottom"><span>© 2026 Rijksdienst · Project in ontwikkeling</span><span>Gebouwd met aandacht voor duidelijkheid, bronnen en toegankelijkheid.</span></div>
    </footer>
  </div>
</template>
