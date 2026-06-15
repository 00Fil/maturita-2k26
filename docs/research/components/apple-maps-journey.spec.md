# AppleMapsJourney Specification

## Overview
- **Target file:** `hub.php` section `#w-fine`
- **Screenshot:** user-provided Apple Maps references in chat
- **Interaction model:** click-driven + map-driven; no scroll interaction inside the Maps app

## DOM Structure
- `.maps-window` contains `.maps-titlebar` and `.maps-app[data-maps-navigator]`.
- `.maps-app` is a 2-column grid: `.maps-side` and `.maps-main`.
- `.maps-side`: search bar, labels, recent/favorite rows, `.maps-route-card` with mode tabs and `[data-maps-stops]` container, recent card.
- `.maps-main`: SVG `.maps-map`, `.maps-top-card`, `.maps-controls`, `.maps-compass`, `.maps-bottom-sheet`.
- SVG map groups: background tiles, roads/cities, `#maps-cam`, route base/progress, markers, `#maps-puck`.

## Computed Styles / Tokens
### Window
- background: `rgba(22,24,28,.78)`
- border-radius: `24px`
- shadow: `var(--shadow-win)` + hairline inset

### App
- height: `min(650px, calc(100vh - 120px))`
- grid-template-columns: `292px minmax(0, 1fr)`
- background: `#0b1018`
- foreground: `#f5f5f7`
- font: `SF Pro Display, -apple-system, BlinkMacSystemFont, Helvetica Neue, Arial`

### Sidebar
- background: vertical dark gradient `rgba(21,28,37,.96)` to `rgba(10,17,26,.96)`
- border-right: `1px solid rgba(255,255,255,.10)`
- padding: `12px`

### Top Card
- position: absolute top-left, width 344px
- border-radius: 21px
- background: `linear-gradient(180deg,#31ba53,#15913a)`
- box-shadow: green Apple Maps depth
- animation: `mapsPanelIn .55s cubic-bezier(.32,.72,0,1)`

### Bottom Sheet
- position: absolute left/right/bottom 16px
- border-radius: 21px
- background: `rgba(248,248,250,.80)`
- backdrop-filter: `blur(16px) saturate(1.75)`
- animation: `mapsSheetIn .55s cubic-bezier(.32,.72,0,1)`

## States & Behaviors
### Tappa attiva
- **Trigger:** click on `[data-maps-step]`, `.maps-dot`, `.maps-marker`, `.maps-map`, or `[data-maps-next]`.
- **State change:** `current` index updates; camera transform, puck transform, route progress, active sidebar rows/dots/markers and text content update.
- **Transition:** `1.05s cubic-bezier(.32,.72,0,1)` for map camera/route/puck.

### Zoom
- **Trigger:** click `data-maps-zoom="in|out"`.
- **State:** zoom clamps between `.82` and `1.45`; `render()` recomputes transform.

### Reset
- **Trigger:** click `[data-maps-reset]`.
- **State:** zoom becomes 1.

## Per-State Content
1. **FSL (PCTO)** — esperienza pratica, primo contatto con lavoro/metodo.
2. **Diploma** — chiusura maturità e consolidamento competenze.
3. **Università a Brescia** — Ingegneria Informatica, basi solide software.
4. **America / estero** — ricerca opportunità internazionale di carriera.

## Assets
- No external assets required for the map; SVG generated inline.
- Existing app icon: `assets/icons/maps.webp` with remote fallback.

## Responsive Behavior
- **Desktop 1440px:** sidebar + full map canvas.
- **Tablet 768px / Mobile 390px:** `.maps-side` hidden, top card width 300px, bottom sheet tighter, window width `92vw`.
