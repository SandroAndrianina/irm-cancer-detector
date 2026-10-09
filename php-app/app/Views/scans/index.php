<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>IRM Cancer Detector</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; }
    fieldset { border: 1px solid #ddd; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; }
    legend { font-weight: 600; padding: 0 .5rem; }
    label { display: inline-block; margin-right: 1rem; }
    input, select, button { padding: .4rem .6rem; font: inherit; }
    button { cursor: pointer; }
    table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
    th, td { border: 1px solid #ddd; padding: .5rem .75rem; text-align: left; }
    th { background: #f4f4f4; }
    .malin      { color: #b00020; font-weight: bold; }
    .benin      { color: #0a7a29; font-weight: bold; }
    .en_attente { color: #b06f00; font-weight: bold; }
    #err, #ok { margin-top: 1rem; }
    #err { color: #b00020; }
    #ok  { color: #0a7a29; }
  </style>
</head>
<body>
  <h1>IRM Cancer Detector</h1>

  <fieldset>
    <legend>Upload an MRI scan</legend>
    <form id="upload" enctype="multipart/form-data">
      <label>Patient <input name="patientRef" placeholder="PAT-006" required></label>
      <label>Zone
        <select name="zone">
          <option>brain</option><option>breast</option><option>liver</option><option>spine</option>
        </select>
      </label>
      <label><input type="checkbox" name="contrastUsed" value="1"> contrast dye</label>
      <label>Image <input type="file" name="image" accept="image/png,image/jpeg" required></label>
      <button type="submit">Analyze</button>
    </form>
    <div id="err"></div>
    <div id="ok"></div>
  </fieldset>

  <button id="refresh">Refresh from Java</button>
  <table>
    <thead><tr>
      <th>ID</th><th>Patient</th><th>Zone</th><th>Contrast</th>
      <th>Intensity</th><th>Result</th><th>Created</th>
    </tr></thead>
    <tbody id="rows"></tbody>
  </table>

<script>
const tbody = document.getElementById('rows');
const err   = document.getElementById('err');
const ok    = document.getElementById('ok');

async function load() {
  tbody.innerHTML = '<tr><td colspan="7">Loading…</td></tr>';
  try {
    const res  = await fetch('/api/scans');
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || res.statusText);
    tbody.innerHTML = '';
    for (const s of data) {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${s.id}</td><td>${s.patientRef}</td><td>${s.zone}</td>
        <td>${s.contrastUsed ? 'yes' : 'no'}</td><td>${s.signalIntensity}</td>
        <td class="${s.resultat}">${s.resultat}</td><td>${s.createdAt}</td>`;
      tbody.appendChild(tr);
    }
  } catch (e) {
    tbody.innerHTML = '';
    err.textContent = 'Load error: ' + e.message;
  }
}

document.getElementById('upload').addEventListener('submit', async (ev) => {
  ev.preventDefault();
  err.textContent = ''; ok.textContent = '';
  const fd = new FormData(ev.target);
  try {
    const res  = await fetch('/api/scans', { method: 'POST', body: fd });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || res.statusText);
    ok.textContent = `Saved scan #${data.id} (${data.patientRef}) → ${data.resultat} [intensity=${data.signalIntensity}]`;
    ev.target.reset();
    load();
  } catch (e) {
    err.textContent = 'Upload error: ' + e.message;
  }
});

document.getElementById('refresh').addEventListener('click', load);
load();
</script>
</body>
</html>