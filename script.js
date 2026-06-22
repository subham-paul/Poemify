let synth = window.speechSynthesis;
let utterance = null;

const samples = [
    "A lonely lighthouse guarding a stormy sea at midnight.",
    "The feeling of drinking hot coffee on a rainy Sunday morning.",
    "A forgotten clock in an abandoned mansion that suddenly starts ticking.",
    "The silent conversation between two old trees in an ancient forest.",
    "A robot discovering its first human emotion while watching a sunset.",
    "The internal struggle of a star deciding whether to go supernova.",
    "The smell of old books in a library that holds secret portals.",
    "A city street at 3 AM where the shadows come alive to dance.",
    "The echo of laughter in a childhood playground now covered in snow.",
    "A traveller journeying through a galaxy made of crystal and light."
];

function getSamplePrompt() {
    const promptInput = document.getElementById('prompt');
    const randomPrompt = samples[Math.floor(Math.random() * samples.length)];
    promptInput.value = randomPrompt;
}

document.getElementById('generate-btn').addEventListener('click', async () => {
    const prompt = document.getElementById('prompt').value;
    const type = document.getElementById('type').value;
    const style = document.getElementById('style').value;
    const length = document.getElementById('length').value;
    const loader = document.getElementById('loader');
    const resultContainer = document.getElementById('result-container');
    const output = document.getElementById('poem-output');
    const controls = document.querySelector('.controls');

    if (!prompt) {
        alert("Please enter a prompt!");
        return;
    }

    if (synth.speaking) {
        synth.cancel();
    }
    updateTTSButton(false);

    loader.style.display = 'block';
    resultContainer.style.display = 'none';
    
    try {
        const response = await fetch('generate.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `prompt=${encodeURIComponent(prompt)}&type=${encodeURIComponent(type)}&style=${encodeURIComponent(style)}&length=${encodeURIComponent(length)}`
        });
        
        const data = await response.json();
        
        if (data.poem) {
            output.innerText = data.poem;
            resultContainer.style.display = 'block';
            controls.style.display = 'flex';
            
            // Scroll to output
            setTimeout(() => {
                output.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);

        } else {
            alert("Error: " + (data.error || "Unknown error"));
        }
    } catch (e) {
        console.error(e);
        alert("Failed to generate poem.");
    } finally {
        loader.style.display = 'none';
    }
});

async function savePoem() {
    const prompt = document.getElementById('prompt').value;
    const type = document.getElementById('type').value;
    const style = document.getElementById('style').value;
    const length = document.getElementById('length').value;
    const content = document.getElementById('poem-output').innerText;
    const saveBtn = document.getElementById('save-btn');

    if (!content) {
        alert("No poem to save!");
        return;
    }

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';

    try {
        const response = await fetch('save_poem.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `prompt=${encodeURIComponent(prompt)}&type=${encodeURIComponent(type)}&style=${encodeURIComponent(style)}&length=${encodeURIComponent(length)}&content=${encodeURIComponent(content)}`
        });

        const data = await response.json();

        if (data.success) {
            alert("Poem saved to your collection!");
            saveBtn.innerHTML = '<i class="fas fa-check me-2"></i> Saved';
            saveBtn.classList.replace('btn-outline-primary', 'btn-success');
        } else {
            alert("Error: " + (data.error || "Could not save poem"));
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save to Collection';
        }
    } catch (e) {
        console.error(e);
        alert("An error occurred while saving.");
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save to Collection';
    }
}

function toggleTTS() {
    const text = document.getElementById('poem-output').innerText;

    if (synth.speaking) {
        if (synth.paused) {
            synth.resume();
            updateTTSButton(true);
        } else {
            synth.pause();
            updateTTSButton(false, true);
        }
    } else {
        synth.cancel();
        utterance = new SpeechSynthesisUtterance(text);
        
        const voices = synth.getVoices();
        if (voices.length > 0) {
            utterance.voice = voices[0]; 
        }

        utterance.onstart = () => updateTTSButton(true);
        utterance.onend = () => updateTTSButton(false);
        utterance.onerror = () => updateTTSButton(false);

        window.currentUtterance = utterance;
        synth.speak(utterance);
    }
}

function updateTTSButton(isSpeaking, isPaused = false) {
    const btn = document.getElementById('tts-btn');
    if (!btn) return;
    
    if (isPaused) {
        btn.innerHTML = '<i class="fas fa-play"></i> Resume';
        btn.className = 'btn btn-info px-4';
    } else if (isSpeaking) {
        btn.innerHTML = '<i class="fas fa-pause"></i> Pause';
        btn.className = 'btn btn-info px-4';
    } else {
        btn.innerHTML = '<i class="fas fa-volume-up"></i> Listen';
        btn.className = 'btn btn-outline-light px-4';
    }
}

function copyPoem() {
    const text = document.getElementById('poem-output').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert("Poem copied to clipboard!");
    });
}

function sharePoem() {
    const text = document.getElementById('poem-output').innerText;
    if (navigator.share) {
        navigator.share({
            title: 'My AI Poem from Poemify',
            text: text,
            url: window.location.href
        });
    } else {
        alert("Sharing not supported on this browser.");
    }
}

if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = () => synth.getVoices();
}