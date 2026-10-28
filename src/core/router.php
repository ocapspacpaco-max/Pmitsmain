<?php
/**
 * The tiny glue that lets modules plug in.
 *
 * A module's module.php calls:
 *   route_action('save_item', 'items_save', 'project', false);   // POST field a=save_item
 *   route_view('tab:board', __DIR__ . '/views/board.php');       // a page/tab template
 *
 * Action scopes:
 *   public  - anyone (login, register, password reset)
 *   user    - any logged-in user (new project, join, leave)
 *   project - needs an approved role in POST pid; handler gets ($pid, $isManager)
 * Handlers return an error string to show on the page, or null. Most end with go() instead.
 */
final class Registry {
  public static $actions = [];
  public static $views = [];
}

function route_action($name, $fn, $scope = 'project', $mgrOnly = false) {
  Registry::$actions[$name] = ['fn' => $fn, 'scope' => $scope, 'mgr' => $mgrOnly];
}
function route_view($key, $file) { Registry::$views[$key] = $file; }
function view_for($key) { return Registry::$views[$key] ?? null; }

/* Runs the POSTed action. Returns an error message (or null). */
function dispatch_post() {
  $a = $_POST['a'] ?? '';
  $r = Registry::$actions[$a] ?? null;

  if ($r && $r['scope'] === 'public') return call_user_func($r['fn']);
  if (!me()) return null;
  if ($r && $r['scope'] === 'user') return call_user_func($r['fn']);

  $pid = (int)($_POST['pid'] ?? 0);
  $role = member($pid);
  if (!$role) die('Forbidden');
  $mgr = $role == 'manager';
  if (!$r || $r['scope'] !== 'project') return null;
  if ($r['mgr'] && !$mgr) return null;
  return call_user_func($r['fn'], $pid, $mgr);
}
