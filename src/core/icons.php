<?php
/* ---------- icons ---------- */
function ic($n, $s = 18) {
  static $I = [
    'layers' => '<path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
    'folder' => '<path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>',
    'plus' => '<path d="M5 12h14"/><path d="M12 5v14"/>',
    'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
    'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
    'arrow-left' => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',
    'chev' => '<path d="m9 18 6-6-6-6"/>',
    'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'layout-dashboard' => '<rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>',
    'columns' => '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/><path d="M15 3v18"/>',
    'list-checks' => '<path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/>',
    'timer' => '<line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/>',
    'rocket' => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>',
    'activity' => '<path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/>',
    'circle-dashed' => '<path d="M10.1 2.18a9.93 9.93 0 0 1 3.8 0"/><path d="M17.6 3.71a9.95 9.95 0 0 1 2.69 2.7"/><path d="M21.82 10.1a9.93 9.93 0 0 1 0 3.8"/><path d="M20.29 17.6a9.95 9.95 0 0 1-2.7 2.69"/><path d="M13.9 21.82a9.94 9.94 0 0 1-3.8 0"/><path d="M6.4 20.29a9.95 9.95 0 0 1-2.69-2.7"/><path d="M2.18 13.9a9.93 9.93 0 0 1 0-3.8"/><path d="M3.71 6.4a9.95 9.95 0 0 1 2.7-2.69"/>',
    'circle-dot' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="1"/>',
    'scan' => '<path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><circle cx="12" cy="12" r="3"/>',
    'circle-check' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
    'bug' => '<path d="m8 2 1.88 1.88"/><path d="M14.12 3.88 16 2"/><path d="M9 7.13v-1a3.003 3.003 0 1 1 6 0v1"/><path d="M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6"/><path d="M12 20v-9"/><path d="M6.53 9C4.6 8.8 3 7.1 3 5"/><path d="M6 13H2"/><path d="M3 21c0-2.1 1.7-3.9 3.8-4"/><path d="M20.97 5c0 2.1-1.6 3.8-3.5 4"/><path d="M22 13h-4"/><path d="M17.2 17c2.1.1 3.8 1.9 3.8 4"/>',
    'file-text' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
    'book-open' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
    'square-check' => '<rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/>',
    'arrow-down' => '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
    'equal' => '<line x1="5" x2="19" y1="9" y2="9"/><line x1="5" x2="19" y1="15" y2="15"/>',
    'arrow-up' => '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
    'chevrons-up' => '<path d="m17 11-5-5-5 5"/><path d="m17 18-5-5-5 5"/>',
    'info' => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
    'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
  ];
  return "<svg class='ic' width='$s' height='$s' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'>" . ($I[$n] ?? '') . "</svg>";
}

/* Put your PMITS logo next to index.php as logo.png and it is used automatically */
function logo() {
  $name = h(config('app.name', 'PMITS'));
  if (file_exists(BASE_PATH . '/logo.png')) return "<img src='logo.png' alt='$name' style='width:150px;max-width:100%;height:auto'>";
  /* map-pin + gear mark and the gradient wordmark (replace by dropping a logo.png next to index.php) */
  return "<svg class='mark' viewBox='0 0 120 140' fill='none' role='img' aria-label='$name logo'><defs><linearGradient id='pmG' x1='10' y1='6' x2='110' y2='135' gradientUnits='userSpaceOnUse'><stop offset='0' stop-color='#4f8cff'/><stop offset='.55' stop-color='#8b5cf6'/><stop offset='1' stop-color='#d946ef'/></linearGradient></defs>"
    . "<path d='M60 5C31 5 9 27 9 55c0 21 12 36 26 51l25 31 25-31c14-15 26-30 26-51C111 27 89 5 60 5Z' stroke='url(#pmG)' stroke-width='4' stroke-linejoin='round' fill='#3b46d6' fill-opacity='.18'/>"
    . "<circle cx='60' cy='55' r='36' stroke='url(#pmG)' stroke-width='3'/>"
    . "<path d='M79.4 50.2 L85.6 50.7 L85.6 59.3 L79.4 59.8 L77.1 65.3 L81.2 70.1 L75.1 76.2 L70.3 72.1 L64.8 74.4 L64.3 80.6 L55.7 80.6 L55.2 74.4 L49.7 72.1 L44.9 76.2 L38.8 70.1 L42.9 65.3 L40.6 59.8 L34.4 59.3 L34.4 50.7 L40.6 50.2 L42.9 44.7 L38.8 39.9 L44.9 33.8 L49.7 37.9 L55.2 35.6 L55.7 29.4 L64.3 29.4 L64.8 35.6 L70.3 37.9 L75.1 33.8 L81.2 39.9 L77.1 44.7Z' fill='url(#pmG)' stroke='url(#pmG)' stroke-width='1' stroke-linejoin='round'/>"
    . "<circle cx='60' cy='55' r='8' fill='#16208f'/></svg>"
    . "<span class='wm'>$name</span>";
}
