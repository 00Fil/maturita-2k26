/* Suoni di interfaccia della pagina di accesso (Web Audio, in stile macOS). */
let sndCtx = null, sndBus = null, sndVerb = null;

function sndAudio() {
	if (!sndCtx) {
		sndCtx = new (window.AudioContext || window.webkitAudioContext)();

		// Catena master: highpass leggero -> lowpass morbido -> destinazione.
		const hp = sndCtx.createBiquadFilter();
		hp.type = 'highpass';
		hp.frequency.value = 120;
		hp.Q.value = 0.4;

		const lp = sndCtx.createBiquadFilter();
		lp.type = 'lowpass';
		lp.frequency.value = 6000;
		lp.Q.value = 0.3;

		const master = sndCtx.createGain();
		master.gain.value = 0.9;

		sndBus = sndCtx.createGain();
		sndBus.connect(hp);
		hp.connect(lp);
		lp.connect(master);
		master.connect(sndCtx.destination);

		// Riverbero corto generato al volo per dare aria ai suoni.
		sndVerb = sndCtx.createConvolver();
		sndVerb.buffer = sndImpulse(1.1, 2.6);
		const verbGain = sndCtx.createGain();
		verbGain.gain.value = 0.18;
		lp.connect(verbGain); // prende il segnale già filtrato
		verbGain.connect(sndVerb);
		sndVerb.connect(master);
	}
	if (sndCtx.state === 'suspended') sndCtx.resume();
	return sndCtx;
}

/* Impulso sintetico per il riverbero (decadimento esponenziale). */
function sndImpulse(seconds, decay) {
	const rate = sndCtx.sampleRate;
	const len = Math.floor(rate * seconds);
	const buf = sndCtx.createBuffer(2, len, rate);
	for (let ch = 0; ch < 2; ch++) {
		const data = buf.getChannelData(ch);
		for (let i = 0; i < len; i++) {
			data[i] = (Math.random() * 2 - 1) * Math.pow(1 - i / len, decay);
		}
	}
	return buf;
}

/* Nota morbida con attacco/rilascio gentili e leggero detune opzionale. */
function sndNote(freq, at, dur, peak, opts = {}) {
	const { type = 'sine', detune = 0, attack = 0.012, release = dur * 0.7 } = opts;

	const o = sndCtx.createOscillator();
	const g = sndCtx.createGain();
	o.type = type;
	o.frequency.value = freq;
	o.detune.value = detune;

	const p = Math.max(peak, 0.0002);
	g.gain.setValueAtTime(0.0001, at);
	// Attacco morbido (curva quasi lineare), poi rilascio esponenziale lungo.
	g.gain.linearRampToValueAtTime(p, at + attack);
	g.gain.setTargetAtTime(0.0001, at + attack, release / 3);

	o.connect(g).connect(sndBus);
	o.start(at);
	o.stop(at + dur + 0.4);
}

/* Stesso volume regolato dal centro di controllo del desktop. */
function sndVol() {
	const v = parseFloat(localStorage.getItem('cc-vol') ?? 25);
	return isNaN(v) ? 0.25 : v / 100;
}

/* Click leggero dei controlli: tick cortissimo con armoniche e un filo di rumore. */
function sndClick() {
	const v = sndVol();
	if (!v) return;
	try {
		const ctx = sndAudio();
		const t = ctx.currentTime + 0.004;

		sndNote(1244.51, t, 0.05, v * 0.045, { attack: 0.004, release: 0.04 });
		sndNote(2489.02, t, 0.035, v * 0.012, { attack: 0.003, release: 0.025 });

		// Microsoffio di rumore filtrato per il "tatto" del click.
		const noise = ctx.createBufferSource();
		const nb = ctx.createBuffer(1, ctx.sampleRate * 0.03, ctx.sampleRate);
		const nd = nb.getChannelData(0);
		for (let i = 0; i < nd.length; i++) nd[i] = (Math.random() * 2 - 1);
		noise.buffer = nb;

		const bp = ctx.createBiquadFilter();
		bp.type = 'bandpass';
		bp.frequency.value = 3200;
		bp.Q.value = 0.8;

		const ng = ctx.createGain();
		ng.gain.setValueAtTime(0.0001, t);
		ng.gain.linearRampToValueAtTime(v * 0.02, t + 0.003);
		ng.gain.setTargetAtTime(0.0001, t + 0.003, 0.012);

		noise.connect(bp).connect(ng).connect(sndBus);
		noise.start(t);
		noise.stop(t + 0.05);
	} catch (e) {}
}

/* Accesso riuscito: accordo ascendente con voci sovrapposte e shimmer. */
function sndGo() {
	const v = sndVol();
	if (!v) return;
	try {
		const t = sndAudio().currentTime + 0.02;
		// Do maggiore add9 ascendente, voci che si tengono e si sovrappongono.
		const voices = [
			{ f: 523.25, d: 0.0,  dur: 0.9, g: 0.06 },  // C5
			{ f: 659.25, d: 0.09, dur: 0.9, g: 0.05 },  // E5
			{ f: 783.99, d: 0.18, dur: 0.9, g: 0.045 }, // G5
			{ f: 987.77, d: 0.27, dur: 1.0, g: 0.04 },  // B5
		];
		voices.forEach(({ f, d, dur, g }) => {
			sndNote(f, t + d, dur, v * g, { attack: 0.02, release: dur, detune: -4 });
			sndNote(f, t + d, dur, v * g * 0.5, { attack: 0.02, release: dur, detune: +5 });
		});
	} catch (e) {}
}

document.addEventListener('click', e => {
	if (e.target.closest('button, .dots i')) sndClick();
}, true);