{{$rider->user->name}}
<?php
$totalCommission = getTotalCommissions($rider);
$totalPaid = getTotalPaid($rider->user);
?>
Total Commission: {{$totalCommission}}<br>

Paid Commission : {{$totalPaid}}<br>

Commission Due : {{$totalCommission - $totalPaid}}<br>

<a href="{{route('admin.rider.commission_clear', $rider->id)}}" class="btn btn-success">Clear Commission</a>