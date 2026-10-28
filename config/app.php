<?php
/**
 * Application settings: names, lookup lists, navigation and security limits.
 * Nothing secret lives here, so it is safe to commit.
 * Read values with config('app.statuses'), config('app.security.join_max_fails'), ...
 */
return [
  'name'  => 'PMITS',
  'title' => 'PMITS – Project Management and Issue Tracker',

  /* limits */
  'security' => [
    'reset_ttl_minutes'  => 10,   // how long an emailed reset code stays valid
    'reset_max_attempts' => 5,    // wrong reset codes allowed before a new code is required
    'join_max_fails'     => 5,    // wrong team codes before the lock starts
    'join_lock_seconds'  => 600,  // length of that lock
    'login_max_fails'    => 5,    // wrong passwords per email+IP before login is paused
    'login_lock_seconds' => 300,  // how long that pause lasts (also the counting window)
    'remember_days'      => 30,   // how long "Remember me" keeps someone logged in
  ],

  /* item workflow: key => label */
  'statuses'       => ['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'In Review', 'done' => 'Done'],
  'status_icons'   => ['todo' => 'circle-dashed', 'in_progress' => 'circle-dot', 'review' => 'scan', 'done' => 'circle-check'],

  'types'          => ['requirement' => 'Requirement', 'story' => 'User Story', 'task' => 'Task', 'bug' => 'Bug'],
  'types_plural'   => ['requirement' => 'Requirements', 'story' => 'User Stories', 'task' => 'Tasks', 'bug' => 'Bugs'],
  'type_icons'     => ['requirement' => 'file-text', 'story' => 'book-open', 'task' => 'square-check', 'bug' => 'bug'],

  'priorities'     => ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'],
  'priority_icons' => ['low' => 'arrow-down', 'medium' => 'equal', 'high' => 'arrow-up', 'critical' => 'chevrons-up'],

  'roles'          => ['manager' => 'Manager', 'developer' => 'Developer', 'tester' => 'Tester'],

  /* sidebar tabs inside a project: key => [label, icon] */
  'tabs' => [
    'overview' => ['Dashboard', 'layout-dashboard'],
    'board'    => ['Kanban Board', 'columns'],
    'items'    => ['Backlog & Issues', 'list-checks'],
    'sprints'  => ['Sprints', 'timer'],
    'releases' => ['Releases', 'rocket'],
    'team'     => ['Team', 'users'],
    'activity' => ['Activity', 'activity'],
  ],

  /* page heading + subtitle for every tab (including the hidden "item" edit screen) */
  'captions' => [
    'overview' => ['Dashboard', 'Your project at a glance'],
    'board'    => ['Kanban Board', 'Move work from To Do to Done'],
    'items'    => ['Backlog & Issues', 'Everything that needs to be done'],
    'item'     => ['Edit Item', 'Update the details'],
    'sprints'  => ['Sprints', 'Short work periods with a goal'],
    'releases' => ['Releases', 'Versions you deliver'],
    'team'     => ['Team', 'Who works on this project'],
    'activity' => ['Activity', 'Recent changes in this project'],
  ],
];
