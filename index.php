<?php

// We were particularly interested in getting the date and seeing if an event is sold out...  But it looks like we can easily grab all events made by a user and, with auth key, anything you could ever want about an event public and private.


// Get Eventbrite vendor packages
require_once('vendor/autoload.php');

// Thanks Jamie!
use jamiehollern\eventbrite\Eventbrite;

// $auth_key is in uncommitted seperate file
include('auth_key.php');
// The auth key is in 1password under: 
// 'Eventbrite bryant@fb private api token'

// Make an eventbrite obj
$eventbrite = new Eventbrite($auth_key);

// Returns true if you can connect.
$can_connect = $eventbrite->canConnect();

// Get all the events I own
$events = $eventbrite->get('users/me/owned_events/');

// I made a fake event, it'll be first

// Let's grab some info from first event....
$date= $events["headers"]["Date"][0];
$title = $events["body"]["events"][0]["name"]["text"];
$id = $events["body"]["events"][0]["id"];

// We need another call for ticket info for that first event...
$tickets = $eventbrite->get('events/'.$id.'/ticket_classes/');

// Just grab the ticket classes
$ticket_classes = $tickets["body"]["ticket_classes"]

?>

<h1>Event Brite API test:</h1>

<p><?= $can_connect ? "Connection successful!" : "No connection" ?></p>
<p>Title: <?= $title ?></p>
<p>Date: <?= $date ?></p>
<p>ID: <?= $id ?></p>

<h3>Tickets</h3>

<ul>
<?php foreach($ticket_classes as $class): 
  $name = $class["name"];
  $status = $class["on_sale_status"];
  $total = $class["quantity_total"];
  $sold = $class["quantity_sold"];
?>

  <li><?= $name ?>
    <ul>
      <li>Status: <?= $status ?></li>
      <li>Total: <?= $total ?></li>
      <li>Sold: <?= $sold ?></li>
      <li><?= $status==='SOLD_OUT' ? "I'm sold out. :(" : "Tickets in this class are available!" ?></li>
    </ul>
  </li>

<?php endforeach; ?>
</ul>

<!-- Let's just dump it all out, while we're at it.... -->

<h2 style="margin-top: 10em;">Ticket Classes:</h2>

<pre>
<?= var_dump($ticket_classes); ?>
</pre>

<h2 style="margin-top: 10em;">All Events:</h2>

<pre>
<?= var_dump($events); ?>
</pre>
