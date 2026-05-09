<!DOCTYPE html>

<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Fiacre KPANOU — Signaux Trading Gold. 8 000 pips/mois. Rejoins la communauté elite du trading en Afrique francophone.">
<title>Fiacre KPANOU — Signaux Trading Gold | Accès Exclusif</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,700&family=Outfit:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
/* ════════════════════════════════════════
   DESIGN TOKENS
════════════════════════════════════════ */
:root {
  --gold:        #C8A84B;
  --gold-light:  #E2C97E;
  --gold-dim:    rgba(200,168,75,0.15);
  --gold-border: rgba(200,168,75,0.25);
  --black:       #070707;
  --ink:         #0D0D0D;
  --surface:     #131313;
  --surface2:    #1A1A1A;
  --surface3:    #222222;
  --cream:       #F2EDE4;
  --muted:       rgba(242,237,228,0.5);
  --subtle:      rgba(242,237,228,0.25);

  --r-sm:  8px;
  --r-md:  14px;
  --r-lg:  22px;
  --r-xl:  32px;

  --ease-out: cubic-bezier(0.16,1,0.3,1);
  --ease-in:  cubic-bezier(0.4,0,1,1);
}

/* ════════════════════════════════════════
   RESET & BASE
════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; font-size: 16px; }

body {
  background: var(--black);
  color: var(--cream);
  font-family: 'Outfit', sans-serif;
  font-weight: 400;
  line-height: 1.65;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
}

/* Grain overlay */
body::after {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 9999;
  opacity: 0.6;
}

img { max-width: 100%; display: block; }
a { text-decoration: none; color: inherit; }

/* ════════════════════════════════════════
   UTILITIES
════════════════════════════════════════ */
.container  { max-width: 1140px; margin: 0 auto; padding: 0 24px; }
.container--sm { max-width: 780px; margin: 0 auto; padding: 0 24px; }
.gold       { color: var(--gold); }
.serif      { font-family: 'Cormorant Garamond', Georgia, serif; }
.mono       { font-family: 'JetBrains Mono', monospace; }

.eyebrow {
  display: inline-flex; align-items: center; gap: 10px;
  font-size: 11px; font-weight: 500; letter-spacing: 0.14em;
  text-transform: uppercase; color: var(--gold); margin-bottom: 18px;
}
.eyebrow::before {
  content: ''; width: 28px; height: 1px; background: var(--gold);
}

.section-heading {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(36px, 4.5vw, 58px);
  font-weight: 700; line-height: 1.1; margin-bottom: 20px;
}

.section-body {
  font-size: 17px; color: var(--muted); font-weight: 300;
  line-height: 1.75; max-width: 560px;
}

.divider-gold {
  width: 48px; height: 2px; background: var(--gold);
  margin: 24px 0;
}

/* ════════════════════════════════════════
   SCROLL REVEAL
════════════════════════════════════════ */
.reveal {
  opacity: 0;
  transform: translateY(36px);
  transition: opacity 0.75s var(--ease-out), transform 0.75s var(--ease-out);
}
.reveal.visible { opacity: 1; transform: none; }
.reveal-d1 { transition-delay: 0.1s; }
.reveal-d2 { transition-delay: 0.2s; }
.reveal-d3 { transition-delay: 0.3s; }
.reveal-d4 { transition-delay: 0.4s; }
/* ════════════════════════════════════════
   BUTTONS
════════════════════════════════════════ */
.btn-gold {
  display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  background: var(--gold); color: var(--black);
  font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 600;
  padding: 18px 44px; border-radius: 100px; border: none; cursor: pointer;
  transition: transform 0.2s var(--ease-out), box-shadow 0.2s;
  box-shadow: 0 0 50px rgba(200,168,75,0.3);
  letter-spacing: 0.02em;
  white-space: nowrap;
}
.btn-gold:hover  { transform: translateY(-3px); box-shadow: 0 8px 60px rgba(200,168,75,0.45); }
.btn-gold:active { transform: scale(0.97); }
.btn-gold--lg    { font-size: 18px; padding: 22px 56px; }

.btn-outline {
  display: inline-flex; align-items: center; gap: 8px;
  background: transparent; color: var(--gold);
  border: 1px solid var(--gold-border); border-radius: 100px;
  font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 500;
  padding: 12px 28px; cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}
.btn-outline:hover { background: var(--gold-dim); border-color: var(--gold); }

/* ════════════════════════════════════════
   TOP BAR
════════════════════════════════════════ */
#topbar {
  background: var(--gold); color: var(--black);
  text-align: center; padding: 11px 16px;
  font-size: 13px; font-weight: 600; letter-spacing: 0.04em;
  position: sticky; top: 0; z-index: 200;
}
#topbar span { font-weight: 400; opacity: 0.7; margin: 0 10px; }

/* ════════════════════════════════════════
   HERO
════════════════════════════════════════ */
#hero {
  min-height: 100vh;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  text-align: center; padding: 80px 24px 60px;
  position: relative; overflow: hidden;
}

.hero-bg-grid {
  position: absolute; inset: 0; pointer-events: none;
  background-image:
    linear-gradient(rgba(200,168,75,0.045) 1px, transparent 1px),
    linear-gradient(90deg, rgba(200,168,75,0.045) 1px, transparent 1px);
  background-size: 64px 64px;
}

.hero-bg-glow {
  position: absolute; inset: 0; pointer-events: none;
  background: radial-gradient(ellipse 80% 60% at 50% 40%, rgba(200,168,75,0.08) 0%, transparent 70%);
}

.hero-badge {
  display: inline-flex; align-items: center; gap: 10px;
  border: 1px solid var(--gold-border); border-radius: 100px;
  padding: 9px 22px; font-size: 12px; font-weight: 500;
  letter-spacing: 0.1em; text-transform: uppercase; color: var(--gold);
  margin-bottom: 36px;
  animation: fadeDown 0.9s var(--ease-out) both;
}
.badge-dot {
  width: 7px; height: 7px; border-radius: 50%; background: var(--gold);
  animation: pulse-dot 2.2s infinite;
}
@keyframes pulse-dot {
  0%,100% { opacity:1; transform:scale(1); }
  50%      { opacity:0.35; transform:scale(0.65); }
}

.hero-title {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(52px, 8.5vw, 104px);
  font-weight: 700; line-height: 1.02;
  margin-bottom: 28px;
  animation: fadeUp 0.9s 0.15s var(--ease-out) both;
}
.hero-title em {
  font-style: italic; color: var(--gold);
  display: block;
}

.hero-sub {
  font-size: clamp(17px, 2.2vw, 21px); color: var(--muted);
  max-width: 600px; font-weight: 300; line-height: 1.7;
  margin: 0 auto 52px;
  animation: fadeUp 0.9s 0.28s var(--ease-out) both;
}

/* Countdown */
.countdown-block {
  margin-bottom: 52px;
  animation: fadeUp 0.9s 0.42s var(--ease-out) both;
}
.cd-label {
  font-size: 11px; letter-spacing: 0.13em; text-transform: uppercase;
  color: var(--subtle); margin-bottom: 16px;
}
.countdown {
  display: flex; align-items: center; justify-content: center; gap: 10px;
}
.cd-unit {
  display: flex; flex-direction: column; align-items: center;
  background: var(--surface); border: 1px solid var(--gold-border);
  border-radius: var(--r-md); padding: 16px 22px; min-width: 78px;
}
.cd-num {
  font-family: 'JetBrains Mono', monospace; font-size: 38px;
  font-weight: 500; color: var(--gold); line-height: 1;
}
.cd-tag {
  font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em;
  color: var(--subtle); margin-top: 7px;
}
.cd-sep {
  font-size: 30px; color: var(--gold-border); font-weight: 300;
  padding-bottom: 14px;
}

/* Hero CTA */
.hero-cta {
  animation: fadeUp 0.9s 0.56s var(--ease-out) both;
}
.cta-meta {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  margin-top: 16px; font-size: 13px; color: var(--subtle);
}
.cta-meta::before, .cta-meta::after {
  content: ''; flex: 1; max-width: 50px; height: 1px;
  background: var(--gold-border);
}

/* ════════════════════════════════════════
   VIDEO SECTION
════════════════════════════════════════ */
#video-section {
  padding: 20px 0 90px;
  background: var(--black);
  text-align: center;
}

.video-container {
  max-width: 900px; margin: 0 auto;
  border-radius: var(--r-lg); overflow: hidden;
  border: 1px solid var(--gold-border);
  box-shadow: 0 0 100px rgba(200,168,75,0.1);
  position: relative;
}

.video-embed {
  width: 100%; aspect-ratio: 16/9; display: block;
  background: var(--surface); border: none;
}

/* Placeholder shown before real video is added */
.video-placeholder {
  width: 100%; aspect-ratio: 16/9;
  background: var(--surface);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 20px;
  cursor: pointer; position: relative; overflow: hidden;
}
.video-placeholder::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at center, rgba(200,168,75,0.06) 0%, transparent 70%);
}
.play-ring {
  width: 80px; height: 80px; border-radius: 50%;
  border: 2px solid rgba(200,168,75,0.4);
  display: flex; align-items: center; justify-content: center;
  transition: transform 0.3s var(--ease-out), border-color 0.3s;
  position: relative; z-index: 1;
}
.play-ring::after {
  content: '';
  position: absolute; inset: -8px;
  border-radius: 50%;
  border: 1px solid rgba(200,168,75,0.15);
  animation: ring-pulse 2.5s infinite;
}
@keyframes ring-pulse {
  0%   { transform: scale(1);   opacity: 1; }
  100% { transform: scale(1.5); opacity: 0; }
}
.play-icon {
  width: 56px; height: 56px; border-radius: 50%;
  background: var(--gold); display: flex; align-items: center; justify-content: center;
  transition: transform 0.3s, box-shadow 0.3s;
}
.video-placeholder:hover .play-icon {
  transform: scale(1.1);
  box-shadow: 0 0 40px rgba(200,168,75,0.5);
}
.play-triangle {
  width: 0; height: 0;
  border-top: 11px solid transparent;
  border-bottom: 11px solid transparent;
  border-left: 20px solid var(--black);
  margin-left: 4px;
}
.video-placeholder-text {
  font-size: 14px; color: var(--muted); position: relative; z-index: 1;
}

/* ════════════════════════════════════════
   STATS BAR
════════════════════════════════════════ */
#stats {
  background: var(--surface);
  border-top: 1px solid var(--gold-border);
  border-bottom: 1px solid var(--gold-border);
  padding: 44px 0;
}
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 8px;
}
.stat-item {
  text-align: center; padding: 12px 8px;
  position: relative;
}
.stat-item:not(:last-child)::after {
  content: '';
  position: absolute; right: 0; top: 20%; bottom: 20%;
  width: 1px; background: var(--gold-border);
}
.stat-num {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-size: clamp(36px, 4vw, 52px); font-weight: 700;
  color: var(--gold); line-height: 1; display: block; margin-bottom: 8px;
}
.stat-label {
  font-size: 13px; color: var(--muted); font-weight: 400; line-height: 1.4;
}

/* ════════════════════════════════════════
   WHO AM I
════════════════════════════════════════ */
#who {
  padding: 110px 0;
  background: var(--ink);
}
.who-grid {
  display: grid;
  grid-template-columns: 420px 1fr;
  gap: 80px; align-items: start;
}
/* Photo column */
.who-photo-wrap { position: relative; }

.who-photo-frame {
  border-radius: var(--r-lg);
  overflow: hidden;
  border: 1px solid var(--gold-border);
  position: relative;
  background: var(--surface);
}
.who-photo-frame img {
  width: 100%; display: block;
  object-fit: cover; object-position: top center;
}

/* Placeholder until photo is added */
.photo-placeholder {
  aspect-ratio: 3/4;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 16px;
  background: var(--surface);
  border: 2px dashed rgba(200,168,75,0.2);
  border-radius: var(--r-lg);
}
.photo-monogram {
  width: 96px; height: 96px; border-radius: 50%;
  background: linear-gradient(135deg, #5a4010, var(--gold));
  display: flex; align-items: center; justify-content: center;
  font-family: 'Cormorant Garamond', serif;
  font-size: 38px; font-weight: 700; color: var(--black);
}
.photo-hint {
  font-size: 13px; color: rgba(200,168,75,0.4);
  text-align: center; line-height: 1.6; padding: 0 24px;
}

/* Credential card overlapping photo */
.who-cred-card {
  background: var(--surface2);
  border: 1px solid var(--gold-border);
  border-radius: var(--r-md);
  padding: 22px 24px; margin-top: 20px;
}
.who-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 24px; font-weight: 700; margin-bottom: 4px;
}
.who-role { font-size: 13px; color: var(--gold); font-weight: 500; margin-bottom: 18px; }

.trophy-list { list-style: none; }
.trophy-list li {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid rgba(200,168,75,0.08);
  font-size: 14px; color: var(--muted);
}
.trophy-list li:last-child { border-bottom: none; }
.trophy-icon {
  width: 30px; height: 30px; border-radius: var(--r-sm);
  background: var(--gold-dim); display: flex; align-items: center;
  justify-content: center; font-size: 14px; flex-shrink: 0; margin-top: 1px;
}

/* Text column */
.who-text .section-body { margin-bottom: 36px; }
.check-list { list-style: none; }
.check-list li {
  display: flex; align-items: center; gap: 14px;
  padding: 11px 0;
  border-bottom: 1px solid rgba(200,168,75,0.07);
  font-size: 15px; color: var(--muted);
}
.check-list li:last-child { border-bottom: none; }
.check-icon {
  width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
  border: 1px solid var(--gold); background: var(--gold-dim);
  display: flex; align-items: center; justify-content: center;
  color: var(--gold); font-size: 11px; font-weight: 700;
}

/* ════════════════════════════════════════
   HOW IT WORKS
════════════════════════════════════════ */
#how {
  padding: 110px 0;
  background: var(--black);
  text-align: center;
}
.steps-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px; margin-top: 70px;
  position: relative;
}
.steps-line {
  position: absolute; top: 42px; left: calc(16.5% + 14px); right: calc(16.5% + 14px);
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold-border) 20%, var(--gold) 50%, var(--gold-border) 80%, transparent);
}
.step-card {
  background: var(--surface);
  border: 1px solid var(--gold-border);
  border-radius: var(--r-lg); padding: 44px 28px 36px;
  transition: border-color 0.3s, transform 0.3s var(--ease-out);
  position: relative;
}
.step-card:hover {
  border-color: rgba(200,168,75,0.6); transform: translateY(-6px);
}
.step-num {
  width: 56px; height: 56px; border-radius: 50%;
  border: 2px solid var(--gold); background: var(--black);
  display: flex; align-items: center; justify-content: center;
  font-family: 'JetBrains Mono', monospace;
  font-size: 18px; font-weight: 500; color: var(--gold);
  margin: 0 auto 28px; position: relative; z-index: 1;
}
.step-card h3 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 24px; font-weight: 700; margin-bottom: 14px;
}
.step-card p {
  font-size: 14px; color: var(--muted); line-height: 1.75;
}

/* ════════════════════════════════════════
   RESULTS
════════════════════════════════════════ */
#results {
  padding: 110px 0;
  background: var(--surface);
}
.results-grid {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 52px; align-items: center; margin-top: 70px;
}
.result-hero-card {
  background: var(--ink);
  border: 1px solid var(--gold-border);
  border-radius: var(--r-xl); padding: 56px 48px;
  text-align: center; position: relative; overflow: hidden;
}
.result-hero-card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.result-hero-card::after {
  content: '';
  position: absolute; bottom: -80px; right: -80px;
  width: 220px; height: 220px; border-radius: 50%;
  background: radial-gradient(circle, rgba(200,168,75,0.08) 0%, transparent 70%);
}
.result-big {
  font-family: 'Cormorant Garamond', serif;
  font-size: clamp(72px, 9vw, 112px);
  font-weight: 700; color: var(--gold); line-height: 1;
  display: block; margin-bottom: 12px;
}
.result-big-sub {
  font-size: 18px; color: var(--muted); font-weight: 300;
}
.result-pill {
  display: inline-block; margin-top: 24px;
  background: var(--gold-dim); border: 1px solid var(--gold-border);
  border-radius: 100px; padding: 7px 20px;
  font-size: 12px; color: var(--gold); letter-spacing: 0.05em;
}

.result-metrics { display: flex; flex-direction: column; gap: 20px; }
.metric-card {
  background: var(--ink); border: 1px solid var(--gold-border);
  border-radius: var(--r-md); padding: 24px 28px;
  display: flex; align-items: center; gap: 20px;
  transition: border-color 0.3s;
}
.metric-card:hover { border-color: rgba(200,168,75,0.5); }
.metric-icon {
  width: 46px; height: 46px; border-radius: var(--r-sm);
  background: var(--gold-dim); flex-shrink: 0;
  display: flex; align-items: center; justify-content: center; font-size: 20px;
}
.metric-num {
  font-family: 'Cormorant Garamond', serif;
  font-size: 30px; font-weight: 700; color: var(--gold); line-height: 1;
}
.metric-label {
  font-size: 13px; color: var(--muted); margin-top: 4px;
}

/* ════════════════════════════════════════
   TEXT TESTIMONIALS
════════════════════════════════════════ */
#testi {
  padding: 110px 0;
  background: var(--black);
  text-align: center;
}
.testi-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 24px; margin-top: 64px;
}
.testi-card {
  background: var(--surface); border: 1px solid var(--gold-border);
  border-radius: var(--r-lg); padding: 36px 30px; text-align: left;
  transition: transform 0.3s var(--ease-out), border-color 0.3s;
}
.testi-card:hover {
  transform: translateY(-5px);
  border-color: rgba(200,168,75,0.45);
}
.testi-stars {
  display: flex; gap: 4px; margin-bottom: 18px;
}
.star { color: var(--gold); font-size: 15px; }
.testi-quote {
  font-size: 14px; color: var(--muted); line-height: 1.75;
  font-style: italic; margin-bottom: 26px;
}
.testi-author { display: flex; align-items: center; gap: 12px; }
.testi-avatar {
  width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #5a4010, var(--gold));
  display: flex; align-items: center; justify-content: center;
  font-family: 'Cormorant Garamond', serif; font-size: 17px;
  font-weight: 700; color: var(--black);
}
.testi-name  { font-size: 14px; font-weight: 600; }
.testi-city  { font-size: 12px; color: var(--subtle); margin-top: 2px; }

/* ════════════════════════════════════════
   SCREENSHOT TESTIMONIALS
════════════════════════════════════════ */
#screenshots {
  padding: 110px 0;
  background: var(--surface);
  text-align: center;
}
.shots-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px; margin-top: 60px;
}
.shot-frame {border-radius: var(--r-md); overflow: hidden;
  border: 1px solid var(--gold-border);
  background: var(--ink); cursor: pointer;
  transition: transform 0.3s var(--ease-out), border-color 0.3s;
}
.shot-frame:hover {
  transform: translateY(-7px) scale(1.02);
  border-color: rgba(200,168,75,0.5);
}
.shot-frame img { width: 100%; display: block; object-fit: cover; }

/* Placeholder for screenshots */
.shot-placeholder {
  aspect-ratio: 9/16;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 12px; padding: 20px;
  border: 2px dashed rgba(200,168,75,0.18);
  border-radius: var(--r-md);
}
.shot-placeholder-icon {
  width: 46px; height: 46px; border-radius: var(--r-sm);
  background: var(--gold-dim);
  display: flex; align-items: center; justify-content: center; font-size: 20px;
}
.shot-placeholder-text {
  font-size: 12px; color: rgba(200,168,75,0.35);
  text-align: center; line-height: 1.6;
}
.shot-caption {
  padding: 12px 14px; font-size: 12px; color: var(--muted);
  display: flex; align-items: center; gap: 8px; text-align: left;
}
.shot-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }

.shots-cta-note {
  margin-top: 28px; font-size: 13px; color: var(--subtle);
}

/* ════════════════════════════════════════
   LIGHTBOX
════════════════════════════════════════ */
#lightbox {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.94); z-index: 9998;
  align-items: center; justify-content: center; padding: 24px;
}
#lightbox.open { display: flex; }
#lightbox img {
  max-width: 100%; max-height: 90vh;
  border-radius: var(--r-md); object-fit: contain;
}
.lb-close {
  position: fixed; top: 18px; right: 22px;
  background: rgba(255,255,255,0.08); border: none;
  color: var(--cream); font-size: 22px; cursor: pointer;
  width: 42px; height: 42px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s;
}
.lb-close:hover { background: rgba(255,255,255,0.16); }

/* ════════════════════════════════════════
   OFFER
════════════════════════════════════════ */
#offre {
  padding: 110px 0;
  background: var(--black);
  text-align: center;
}
.offer-card {
  max-width: 700px; margin: 60px auto 0;
  background: var(--surface);
  border: 1px solid var(--gold);
  border-radius: var(--r-xl); padding: 64px 60px;
  position: relative; overflow: hidden;
}
.offer-card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 4px;
  background: linear-gradient(90deg, #5a4010, var(--gold), var(--gold-light), var(--gold), #5a4010);
}
.offer-glow-bg {
  position: absolute; top: -120px; left: 50%;
  transform: translateX(-50%);
  width: 500px; height: 400px; border-radius: 50%;
  background: radial-gradient(circle, rgba(200,168,75,0.07) 0%, transparent 70%);
  pointer-events: none;
}
.offer-badge {
  display: inline-block; background: var(--gold); color: var(--black);
  font-size: 11px; font-weight: 700; letter-spacing: 0.12em;
  text-transform: uppercase; padding: 7px 22px; border-radius: 100px;
  margin-bottom: 30px;
}
.offer-name {
  font-family: 'Cormorant Garamond', serif;
  font-size: 30px; font-weight: 700; margin-bottom: 6px;
}
.offer-desc { font-size: 15px; color: var(--muted); margin-bottom: 32px; }
.offer-price-row {
  display: flex; align-items: baseline;
  justify-content: center; gap: 6px; margin-bottom: 8px;
}
.price-currency {
  font-family: 'Cormorant Garamond', serif;
  font-size: 36px; font-weight: 600; color: var(--gold); line-height: 1;
}
.price-amount {
  font-family: 'Cormorant Garamond', serif;
  font-size: 88px; font-weight: 700; color: var(--gold); line-height: 1;
}
.offer-period {
  font-size: 15px; color: var(--muted); margin-bottom: 44px;
}
.offer-features { text-align: left; margin-bottom: 44px; }
.offer-feat {
  display: flex; align-items: center; gap: 14px;
  padding: 13px 0; border-bottom: 1px solid rgba(200,168,75,0.1);
  font-size: 15px;
}
.offer-feat:last-child { border-bottom: none; }
.feat-check {
  width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
  border: 1px solid var(--gold); background: var(--gold-dim);
  display: flex; align-items: center; justify-content: center;
  color: var(--gold); font-size: 12px; font-weight: 700;
}
.offer-btn {
  display: block; width: 100%;
  background: var(--gold); color: var(--black);
  font-family: 'Outfit', sans-serif;
  font-size: 18px; font-weight: 700;
  padding: 22px; border-radius: 100px; border: none; cursor: pointer;
  transition: transform 0.2s var(--ease-out), box-shadow 0.2s;
  box-shadow: 0 0 50px rgba(200,168,75,0.2);
  letter-spacing: 0.02em;
}
.offer-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 60px rgba(200,168,75,0.4); }
.offer-btn:active { transform: scale(0.98); }
.offer-secure {
  margin-top: 18px; font-size: 13px; color: var(--subtle);
  display: flex; align-items: center; justify-content: center; gap: 6px;
}
.offer-upsell {
  margin-top: 26px; font-size: 13px; color: var(--subtle); line-height: 1.6;
}
.offer-upsell strong { color: var(--gold); }

/* ════════════════════════════════════════
   FAQ
════════════════════════════════════════ */
#faq {
  padding: 110px 0;
  background: var(--ink);
}
.faq-list { margin-top: 60px; }
.faq-item { border-bottom: 1px solid var(--gold-border); }
.faq-trigger {
  width: 100%; background: none; border: none; cursor: pointer;
  color: var(--cream); font-family: 'Outfit', sans-serif;
  font-size: 17px; font-weight: 500;
  display: flex; align-items: center; justify-content: space-between; gap: 20px;
  padding: 24px 0; text-align: left;
  transition: color 0.2s;
}
.faq-trigger:hover { color: var(--gold); }
.faq-icon {
  width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
  border: 1px solid var(--gold-border);
  display: flex; align-items: center; justify-content: center;
  color: var(--gold); font-size: 18px;
  transition: transform 0.35s var(--ease-out), background 0.2s;
}
.faq-item.open .faq-icon {
  transform: rotate(45deg); background: var(--gold-dim);
}
.faq-body {
  overflow: hidden; max-height: 0;
  transition: max-height 0.45s var(--ease-out), padding 0.3s;
  font-size: 15px; color: var(--muted); line-height: 1.8;
}
.faq-item.open .faq-body { max-height: 400px; padding-bottom: 26px; }

/* ════════════════════════════════════════
   FINAL CTA
════════════════════════════════════════ */
#final-cta {
  padding: 130px 0;
  background: var(--black);
  text-align: center; position: relative; overflow: hidden;
}
#final-cta::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse 80% 80% at 50% 50%, rgba(200,168,75,0.07) 0%, transparent 70%);
}
#final-cta .section-body { margin: 0 auto 52px; }
.final-note { margin-top: 18px; font-size: 13px; color: var(--subtle); }

/* ════════════════════════════════════════
   FOOTER
════════════════════════════════════════ */
footer {
  border-top: 1px solid var(--gold-border);
  padding: 40px 0; text-align: center;
  font-size: 13px; color: var(--subtle);
}
.footer-logo {
  font-family: 'Cormorant Garamond', serif;
  font-size: 22px; font-weight: 700; color: var(--gold);
  margin-bottom: 10px;
}
.footer-legal {
  max-width: 680px; margin: 14px auto 0;
  font-size: 12px; color: rgba(242,237,228,0.3); line-height: 1.7;
}

/* ════════════════════════════════════════
   ANIMATIONS
════════════════════════════════════════ */
@keyframes fadeUp   { from { opacity:0; transform:translateY(32px); } to { opacity:1; transform:none; } }
@keyframes fadeDown { from { opacity:0; transform:translateY(-18px); } to { opacity:1; transform:none; } }

/* ════════════════════════════════════════
   RESPONSIVE — MOBILE FIRST
════════════════════════════════════════ */
@media (max-width: 900px) {
  .who-grid    { grid-template-columns: 1fr; gap: 48px; }
  .results-grid { grid-template-columns: 1fr; }
  .steps-row   { grid-template-columns: 1fr; }
  .steps-line  { display: none; }
  .testi-grid  { grid-template-columns: 1fr; }
  .shots-grid  { grid-template-columns: repeat(2, 1fr); }
  .stats-grid  { grid-template-columns: repeat(2, 1fr); }
  .stat-item:nth-child(2)::after { display: none; }
  .offer-card  { padding: 44px 28px; }
}

@media (max-width: 600px) {
  .hero-title  { font-size: 52px; }
  .cd-num      { font-size: 30px; }
  .cd-unit     { padding: 12px 14px; min-width: 64px; }
  .shots-grid  { grid-template-columns: repeat(2, 1fr); }
  .price-amount { font-size: 72px; }
  .btn-gold--lg { padding: 18px 36px; font-size: 16px; }
}
</style>

</head>
<body>

<!-- ── TOP URGENCY BAR ───────────────── -->

<div id="topbar">
  ⚡ OFFRE SPÉCIALE EN COURS <span>—</span> Places limitées · Accès ce soir uniquement
</div>

<!-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ -->

<section id="hero">
  <div class="hero-bg-grid"></div>
  <div class="hero-bg-glow"></div>

  <div class="hero-badge">
    <span class="badge-dot"></span>
    Accès exclusif — Signaux Gold
  </div>

  <h1 class="hero-title">
    Tu es à un seul clic<br>
    <em>de tout changer.</em>
  </h1>

  <p class="hero-sub">
    Reçois chaque jour mes alertes trading sur le Gold directement sur Telegram.
    Tu copies, tu exécutes, tu profites — même si tu n'as jamais tradé de ta vie.
  </p>

  <!-- Countdown -->

  <div class="countdown-block">
    <p class="cd-label">L'offre spéciale expire dans</p>
    <div class="countdown">
      <div class="cd-unit"><span class="cd-num" id="cd-h">00</span><span class="cd-tag">Heures</span></div>
      <span class="cd-sep">:</span>
      <div class="cd-unit"><span class="cd-num" id="cd-m">00</span><span class="cd-tag">Minutes</span></div>
      <span class="cd-sep">:</span>
      <div class="cd-unit"><span class="cd-num" id="cd-s">00</span><span class="cd-tag">Secondes</span></div>
    </div>
  </div>

  <div class="hero-cta">
    <a href="#offre" class="btn-gold btn-gold--lg">Je veux accéder maintenant — 50 $</a>
    <p class="cta-meta">Paiement sécurisé &nbsp;·&nbsp; Accès immédiat &nbsp;·&nbsp; Telegram</p>
  </div>
</section>

<!-- ══════════════════════════════════════
     VIDEO
══════════════════════════════════════ -->

<section id="video-section">
  <div class="container">
    <p class="eyebrow reveal" style="justify-content:center;">Regarde cette vidéo en entier</p>
    <div class="video-container reveal">

  <!--
  ╔══════════════════════════════════════════════════════╗
  ║  POUR AJOUTER TA VIDÉO :                             ║
  ║                                                      ║
  ║  Option YouTube — remplace VIDEO_ID :                ║
  ║  <iframe class="video-embed"                         ║
  ║    src="https://www.youtube.com/embed/VIDEO_ID       ║
  ║    ?rel=0&modestbranding=1"                          ║
  ║    allowfullscreen></iframe>                         ║
  ║                                                      ║
  ║  Option fichier MP4 direct :                         ║
  ║  <video class="video-embed" controls                 ║
  ║    poster="thumbnail.jpg">                           ║
  ║    <source src="video.mp4" type="video/mp4">         ║
  ║  </video>                                            ║
  ╚══════════════════════════════════════════════════════╝
  -->

  <div class="video-placeholder" id="video-ph" onclick="loadVideo()">
    <div class="play-ring">
      <div class="play-icon">
        <div class="play-triangle"></div>
      </div>
    </div>
    <p class="video-placeholder-text">Clique pour regarder la vidéo de présentation</p>
  </div>

</div>
  </div>
</section>

<!-- ══════════════════════════════════════
     STATS BAR
══════════════════════════════════════ -->
<div id="stats">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item reveal reveal-d1">
        <span class="stat-num">10</span>
        <span class="stat-label">Années<br>d'expérience</span>
      </div>
      <div class="stat-item reveal reveal-d2">
        <span class="stat-num">8 000</span>
        <span class="stat-label">Pips Gold<br>par mois</span>
      </div>
      <div class="stat-item reveal reveal-d3">
        <span class="stat-num">3×</span>
        <span class="stat-label">Meilleur trader<br>Afrique francophone</span>
      </div>
      <div class="stat-item reveal reveal-d4">
        <span class="stat-num">5</span>
        <span class="stat-label">Mois de résultats<br>consécutifs vérifiés</span>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════
     QUI SUIS-JE
══════════════════════════════════════ -->

<section id="who">
  <div class="container">
    <div class="who-grid">

  <!-- Colonne photo -->
  <div class="who-photo-wrap reveal">

    <!--
    ╔══════════════════════════════════════╗
    ║  POUR AJOUTER TA PHOTO :             ║
    ║  Remplace le bloc .photo-placeholder ║
    ║  par :                               ║
    ║  <div class="who-photo-frame">       ║
    ║    <img src="fiacre.jpg"             ║
    ║      alt="Fiacre KPANOU">            ║
    ║  </div>                              ║
    ╚══════════════════════════════════════╝
    -->
    <div class="photo-placeholder">
      <div class="photo-monogram">FK</div>
      <div class="photo-hint">
        Ta photo ici<br>
        <small style="opacity:.5;">(remplace ce bloc par &lt;img&gt;)</small>
      </div>
    </div>

    <div class="who-cred-card">
      <div class="who-name">Fiacre KPANOU</div>
      <div class="who-role">Trader indépendant · Gold Specialist</div>
      <ul class="trophy-list">
        <li><div class="trophy-icon">🏆</div>3× Meilleur trader d'Afrique francophone</li>
        <li><div class="trophy-icon">📈</div>Plusieurs millions générés sur les marchés</li>
        <li><div class="trophy-icon">⚡</div>+8 000 pips Gold par mois depuis 5 mois</li>
        <li><div class="trophy-icon">🌍</div>Communauté active en Afrique francophone</li>
      </ul>
    </div>
  </div>

  <!-- Colonne texte -->
  <div class="who-text reveal reveal-d2">
    <span class="eyebrow">Qui suis-je</span>
    <h2 class="section-heading">
      Ce n'est pas<br>de la théorie.<br>
      <em class="gold serif" style="font-style:italic;">C'est du résultat.</em>
    </h2>
    <div class="divider-gold"></div>
    <p class="section-body">
      Cela fait 10 ans que je trade les marchés financiers. J'ai perdu, j'ai appris,
      j'ai dominé. Aujourd'hui je décide de partager exactement ce que je fais
      chaque jour avec ceux qui veulent transformer leur vie financière.
    </p>
    <ul class="check-list" style="margin-top:32px;">
      <li><div class="check-icon">✓</div>Tu n'as pas besoin de comprendre les marchés</li>
      <li><div class="check-icon">✓</div>Tu n'as pas besoin de passer des heures devant un écran</li>
      <li><div class="check-icon">✓</div>Tu as juste besoin de suivre mes instructions exactement</li>
      <li><div class="check-icon">✓</div>Mini formation complète incluse si tu pars de zéro</li>
      <li><div class="check-icon">✓</div>Lien vers les brokers partenaires recommandés</li>
    </ul>
  </div>
</div>
  </div>
</section>

<!-- ══════════════════════════════════════
     COMMENT ÇA MARCHE
══════════════════════════════════════ -->

<section id="how">
  <div class="container">
    <span class="eyebrow reveal" style="justify-content:center;">Méthode</span>
    <h2 class="section-heading reveal" style="text-align:center;">
      Simple comme<br><em class="gold serif" style="font-style:italic;">1 — 2 — 3.</em>
      </h2>
    <div class="steps-row">
      <div class="steps-line"></div>
      <div class="step-card reveal reveal-d1">
        <div class="step-num">01</div>
        <h3>Tu rejoins</h3>
        <p>Tu cliques, tu paies 50$, tu reçois immédiatement ton accès au groupe Telegram privé. En moins de 5 minutes, tu es à l'intérieur.</p>
      </div>
      <div class="step-card reveal reveal-d2">
        <div class="step-num">02</div>
        <h3>Tu reçois</h3>
        <p>Chaque jour, je publie mes alertes directement sur Telegram. Paire, direction, entrée, stop-loss, take-profit. Tout est précisé.</p>
      </div>
      <div class="step-card reveal reveal-d3">
        <div class="step-num">03</div>
        <h3>Tu copies & tu profites</h3>
        <p>Tu reproduis exactement sur ton compte chez ton broker, avec le montant que tu décides. 0 analyse de ta part. Le reste, c'est le marché.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     RÉSULTATS
══════════════════════════════════════ -->

<section id="results">
  <div class="container">
    <span class="eyebrow reveal">Résultats réels</span>
    <h2 class="section-heading reveal">
      Des chiffres réels.<br>
      <em class="gold serif" style="font-style:italic;">Pas des promesses.</em>
    </h2>
    <div class="results-grid">
      <div class="result-hero-card reveal">
        <span class="result-big">8 000</span>
        <div class="result-big-sub">pips sur le Gold chaque mois</div>
        <div class="result-pill">5 mois consécutifs · résultats membres vérifiés</div>
      </div>
      <div class="result-metrics">
        <div class="metric-card reveal reveal-d1">
          <div class="metric-icon">💰</div>
          <div>
            <div class="metric-num">800 USD</div>
            <div class="metric-label">revenus mensuels avec le plus petit lot en suivant chaque instruction</div>
          </div>
        </div>
        <div class="metric-card reveal reveal-d2">
          <div class="metric-icon">🎯</div>
          <div>
            <div class="metric-num">Copy-trade</div>
            <div class="metric-label">tu copies exactement — zéro analyse requise de ta part</div>
          </div>
        </div>
        <div class="metric-card reveal reveal-d3">
          <div class="metric-icon">⚡</div>
          <div>
            <div class="metric-num">Quotidien</div>
            <div class="metric-label">alertes envoyées chaque jour de trading, directement sur Telegram</div>
          </div>
        </div>
        <div class="metric-card reveal reveal-d4">
          <div class="metric-icon">🔒</div>
          <div>
            <div class="metric-num">Stop-loss</div>
            <div class="metric-label">chaque signal inclut une limite de perte définie — tu ne perdras jamais plus que prévu</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     TÉMOIGNAGES TEXTE
══════════════════════════════════════ -->

<section id="testi">
  <div class="container">
    <span class="eyebrow reveal" style="justify-content:center;">Ce que disent les membres</span>
    <h2 class="section-heading reveal" style="text-align:center;">
      Ils ont sauté le pas.<br>
      <em class="gold serif" style="font-style:italic;">Leur vie a changé.</em>
    </h2>
    <div class="testi-grid">
      <div class="testi-card reveal reveal-d1">
        <div class="testi-stars">
          <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
        </div>
        <p class="testi-quote">
          "Je suis comptable, je n'y connaissais rien au trading. En suivant exactement les signaux
          de Fiacre, j'ai fait plus de 600 USD le premier mois. Je n'aurais jamais cru ça possible."
          </p>
        <div class="testi-author">
          <div class="testi-avatar">KA</div>
          <div>
            <div class="testi-name">Kouassi A.</div>
            <div class="testi-city">Abidjan, Côte d'Ivoire</div>
          </div>
        </div>
      </div>
      <div class="testi-card reveal reveal-d2">
        <div class="testi-stars">
          <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
        </div>
        <p class="testi-quote">
          "J'avais essayé d'autres services de signaux. Aucun n'était aussi précis et aussi régulier.
          Avec Fiacre, les résultats sont là chaque mois sans exception. 3 mois et je suis toujours là."
        </p>
        <div class="testi-author">
          <div class="testi-avatar">MD</div>
          <div>
            <div class="testi-name">Moussa D.</div>
            <div class="testi-city">Dakar, Sénégal</div>
          </div>
        </div>
      </div>
      <div class="testi-card reveal reveal-d3">
        <div class="testi-stars">
          <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
        </div>
        <p class="testi-quote">
          "Ce qui m'a convaincu c'est la transparence. Fiacre montre tout — les gains ET les pertes.
          C'est rare. Sur 5 mois, la performance parle d'elle-même. Je recommande à tous mes proches."
        </p>
        <div class="testi-author">
          <div class="testi-avatar">BE</div>
          <div>
            <div class="testi-name">Brice E.</div>
            <div class="testi-city">Cotonou, Bénin</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     CAPTURES TÉMOIGNAGES
══════════════════════════════════════ -->

<section id="screenshots">
  <div class="container">
    <span class="eyebrow reveal" style="justify-content:center;">Preuves réelles</span>
    <h2 class="section-heading reveal" style="text-align:center;">
      Ils ont partagé<br>
      <em class="gold serif" style="font-style:italic;">leurs résultats.</em>
    </h2>
    <p class="section-body reveal" style="margin:0 auto; text-align:center;">
      Captures directes de nos membres sur Telegram. Des vrais trades, de vrais gains, des vraies personnes.
    </p>
    <div class="shots-grid">

`
  <!--
  ╔═══════════════════════════════════════════════╗
  ║  POUR AJOUTER TES CAPTURES MEMBRES :          ║
  ║  Remplace chaque .shot-placeholder par :      ║
  ║  <img src="capture-1.jpg" alt="Résultat">    ║
  ║  à l'intérieur du .shot-frame                 ║
  ╚═══════════════════════════════════════════════╝
  -->

  <div class="shot-frame reveal reveal-d1" onclick="openLightbox(this)">
    <div class="shot-placeholder">
      <div class="shot-placeholder-icon">📱</div>
      <div class="shot-placeholder-text">Capture Telegram<br>Résultat membre 1<br><small>Remplace par ta vraie capture</small></div>
    </div>
    <div class="shot-caption"><span class="shot-dot"></span>Résultats semaine — Dakar</div>
  </div>

  <div class="shot-frame reveal reveal-d2" onclick="openLightbox(this)">
    <div class="shot-placeholder">
      <div class="shot-placeholder-icon">📱</div>
      <div class="shot-placeholder-text">Capture Telegram<br>Résultat membre 2<br><small>Remplace par ta vraie capture</small></div>
    </div>
    <div class="shot-caption"><span class="shot-dot"></span>+620 USD ce mois — Abidjan</div>
  </div>

  <div class="shot-frame reveal reveal-d3" onclick="openLightbox(this)">
    <div class="shot-placeholder">
      <div class="shot-placeholder-icon">📱</div>
      <div class="shot-placeholder-text">Capture Telegram<br>Résultat membre 3<br><small>Remplace par ta vraie capture</small></div>
    </div>
    <div class="shot-caption"><span class="shot-dot"></span>8 000 pips validés — Cotonou</div>
  </div>
  <div class="shot-frame reveal reveal-d4" onclick="openLightbox(this)">
    <div class="shot-placeholder">
      <div class="shot-placeholder-icon">📱</div>
      <div class="shot-placeholder-text">Capture Telegram<br>Résultat membre 4<br><small>Remplace par ta vraie capture</small></div>
    </div>
    <div class="shot-caption"><span class="shot-dot"></span>Retrait confirmé — Douala</div>
  </div>

</div>
<p class="shots-cta-note reveal">Clique sur une capture pour l'agrandir</p>
`

  </div>
</section>

<!-- Lightbox -->

<div id="lightbox">
  <button class="lb-close" onclick="closeLightbox()">✕</button>
  <img id="lb-img" src="" alt="Témoignage agrandi">
</div>

<!-- ══════════════════════════════════════
     OFFRE
══════════════════════════════════════ -->

<section id="offre">
  <div class="container">
    <span class="eyebrow reveal" style="justify-content:center;">L'offre du soir</span>
    <h2 class="section-heading reveal" style="text-align:center;">
      Ton accès complet.<br>
      <em class="gold serif" style="font-style:italic;">Ce soir seulement.</em>
    </h2>
    <div class="offer-card reveal">
      <div class="offer-glow-bg"></div>
      <div class="offer-badge">⚡ Offre spéciale — expire ce soir</div>
      <div class="offer-name">Accès Complet — Signaux Gold</div>
      <div class="offer-desc">Accès immédiat à tout le système. Aucun abonnement caché.</div>
      <div class="offer-price-row">
        <span class="price-currency">$</span>
        <span class="price-amount">50</span>
      </div>
      <div class="offer-period">paiement unique · accès immédiat</div>
      <div class="offer-features">
        <div class="offer-feat"><div class="feat-check">✓</div>Signaux quotidiens sur le Gold via Telegram</div>
        <div class="offer-feat"><div class="feat-check">✓</div>Mini formation complète pour débutants incluse</div>
        <div class="offer-feat"><div class="feat-check">✓</div>Accès au groupe privé de la communauté</div>
        <div class="offer-feat"><div class="feat-check">✓</div>Support et accompagnement personnalisé</div>
        <div class="offer-feat"><div class="feat-check">✓</div>Liens vers les brokers partenaires recommandés</div>
        <div class="offer-feat"><div class="feat-check">✓</div>Accès aux archives des performances passées</div>
      </div>
      <!-- REMPLACE href="#" PAR TON VRAI LIEN DE PAIEMENT -->
      <a href="#" class="offer-btn">Je rejoins maintenant — 50 $</a>
      <p class="offer-secure">🔒 Paiement 100% sécurisé · Accès en moins de 5 minutes</p>
      <p class="offer-upsell">
        Une fois membre, tu pourras opter pour l'offre <strong>3 mois (120$)</strong> ou
        <strong>1 an (350$)</strong> à tarif préférentiel pour maximiser tes revenus.
      </p>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     FAQ
══════════════════════════════════════ -->

<section id="faq">
  <div class="container--sm">
    <span class="eyebrow reveal">Questions fréquentes</span>
    <h2 class="section-heading reveal">
      Tu as des doutes ?<br>
      <em class="gold serif" style="font-style:italic;">On répond à tout.</em>
    </h2>
    <div class="faq-list">
      <div class="faq-item">
        <button class="faq-trigger" onclick="toggleFaq(this)">
          Je n'ai jamais tradé. Est-ce que c'est pour moi ?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-body">
          Absolument. J'ai préparé une mini formation complète incluse dans ton accès. Elle t'explique
          comment ouvrir un compte chez un broker, placer un trade, et lire mes signaux.
          Zéro connaissance préalable requise — si tu peux lire un message Telegram, tu peux trader avec moi.
        </div>
      </div>
      <div class="faq-item"><button class="faq-trigger" onclick="toggleFaq(this)">
          Quel capital minimum est nécessaire pour commencer ?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-body">
          Tu peux commencer avec 100$ de capital de trading. Les 800 USD/mois cités sont basés
          sur le plus petit lot possible (0.01). Plus ton capital est important, plus les gains
          sont proportionnels. Je t'explique exactement comment adapter chaque signal à ton capital.
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-trigger" onclick="toggleFaq(this)">
          Combien de temps dois-je passer par jour ?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-body">
          10 à 15 minutes par jour suffisent. Tu reçois une notification, tu lis le signal,
          tu le places sur ton compte. Pas besoin de surveiller les marchés en continu —
          je fais ça pour toi. Mon système est pensé pour les personnes qui ont une vie active.
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-trigger" onclick="toggleFaq(this)">
          Le trading comporte-t-il des risques ?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-body">
          Oui, comme tout investissement, le trading comporte des risques. Je suis transparent
          sur ce point et je ne te montrerai jamais uniquement les gains. C'est pourquoi chaque signal
          inclut un stop-loss précis — tu ne peux jamais perdre plus que ce que tu as défini à l'avance.
          La gestion du risque est au cœur de ma méthode depuis 10 ans.
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-trigger" onclick="toggleFaq(this)">
          Comment se passe l'accès après le paiement ?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-body">
          Immédiatement après ton paiement, tu reçois un lien d'accès à notre groupe Telegram privé.
          En moins de 5 minutes tu es à l'intérieur, tu reçois la formation de bienvenue et
          tu es prêt pour le prochain signal. Le processus est entièrement automatisé.
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-trigger" onclick="toggleFaq(this)">
          Puis-je upgrader vers une offre longue durée plus tard ?
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-body">
          Oui. Une fois membre et satisfait de tes premiers résultats, tu auras accès à
          des offres exclusives : 3 mois pour 120$ (économie de 30$) ou 1 an pour 350$
          (économie de 250$). Ces offres ne sont proposées qu'aux membres actifs.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     FINAL CTA
══════════════════════════════════════ -->

<section id="final-cta">
  <div class="container" style="text-align:center; position:relative; z-index:1;">
    <span class="eyebrow reveal" style="justify-content:center;">Dernière chance</span>
    <h2 class="section-heading reveal">
      Tu seras acteur<br>
      <em class="gold serif" style="font-style:italic;">ou spectateur ?</em>
    </h2>
    <p class="section-body reveal" style="margin:0 auto 52px;">
      Je suis sur le point de lancer le plus grand mouvement du trading en Afrique
      francophone. Des milliers de personnes vont rejoindre cette aventure.
      La vraie question : tu en fais partie dès le début ?
    </p>
    <div class="reveal">
      <a href="#offre" class="btn-gold btn-gold--lg">Je saisis l'opportunité maintenant</a>
      <p class="final-note">50$ · Accès immédiat · Mini formation incluse · Signaux quotidiens</p>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
<footer>
  <div class="container">
    <div class="footer-logo">Fiacre KPANOU</div>
    <p>© 2025 — Signaux Trading Gold · Tous droits réservés</p>
    <p class="footer-legal">
      Le trading de produits financiers sur marge comporte un niveau de risque élevé et peut ne pas
      convenir à tous les investisseurs. Les performances passées ne garantissent pas les résultats futurs.
      Les résultats présentés sont réels mais non garantis. N'investissez jamais plus que ce que vous
      pouvez vous permettre de perdre.
    </p>
  </div>
</footer>

<!-- ══════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════ -->

<script>
/* ─────────────────────────────────────
   COUNTDOWN — persiste via localStorage
───────────────────────────────────── */
(function() {
  var KEY = 'fk_offer_end';
  var DURATION = 6 * 60 * 60 * 1000; // 6 heures
  var end = localStorage.getItem(KEY);
  if (!end || parseInt(end) < Date.now()) {
    end = Date.now() + DURATION;
    localStorage.setItem(KEY, end);
  }
  end = parseInt(end);

  function pad(n) { return String(n).padStart(2, '0'); }
  function tick() {
    var diff = end - Date.now();
    if (diff < 0) diff = 0;
    var h = Math.floor(diff / 3600000);
    var m = Math.floor((diff % 3600000) / 60000);
    var s = Math.floor((diff % 60000) / 1000);
    document.getElementById('cd-h').textContent = pad(h);
    document.getElementById('cd-m').textContent = pad(m);
    document.getElementById('cd-s').textContent = pad(s);
  }
  tick();
  setInterval(tick, 1000);
})();

/* ─────────────────────────────────────
   SCROLL REVEAL
───────────────────────────────────── */
(function() {
  var els = document.querySelectorAll('.reveal');
  var io = new IntersectionObserver(function(entries) {
    entries.forEach(function(e, i) {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        io.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });
  els.forEach(function(el) { io.observe(el); });
})();

/* ─────────────────────────────────────
   VIDEO
   Remplace VIDEO_ID par ton ID YouTube
───────────────────────────────────── */
function loadVideo() {
  var VIDEO_ID = 'REMPLACE_PAR_TON_VIDEO_ID'; // ex: 'dQw4w9WgXcQ'
  var container = document.querySelector('.video-container');
  container.innerHTML =
    '<iframe class="video-embed" ' +
    'src="https://www.youtube.com/embed/' + VIDEO_ID + '?autoplay=1&rel=0&modestbranding=1" ' +
    'allow="autoplay; fullscreen" allowfullscreen></iframe>';
}

/* ─────────────────────────────────────
   FAQ ACCORDION
───────────────────────────────────── */
function toggleFaq(btn) {
  var item = btn.parentElement;
  var isOpen = item.classList.contains('open');
  document.querySelectorAll('.faq-item.open').forEach(function(i) {
    i.classList.remove('open');
  });
  if (!isOpen) item.classList.add('open');
}

/* ─────────────────────────────────────
   LIGHTBOX CAPTURES
───────────────────────────────────── */
function openLightbox(frame) {
  var img = frame.querySelector('img');
  if (!img) return; // pas encore de vraie image
  document.getElementById('lb-img').src = img.src;
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('lightbox').addEventListener('click', function(e) {
  if (e.target === this) closeLightbox();
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeLightbox();
});

/* ─────────────────────────────────────
   SMOOTH CTA SCROLL
───────────────────────────────────── */
document.querySelectorAll('a[href="#offre"]').forEach(function(a) {
  a.addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('offre').scrollIntoView({ behavior: 'smooth' });
  });
});
</script>

</body>
</html>