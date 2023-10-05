@if ($purchaseCustomer-> type != '')

<ul class="progressbar">

    @if ($purchaseCustomer-> statusReq == 0 || $purchaseCustomer-> statusReq == 1 || $purchaseCustomer-> statusReq == 2)
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</li>
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</li>
    @if ($purchaseCustomer-> type == 'شراء') 
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</li>
    @elseif ($purchaseCustomer-> type == 'رهن')
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}</li>
    @endif
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }}</li>

    @elseif ($purchaseCustomer-> statusReq == 3 || $purchaseCustomer-> statusReq == 4 || $purchaseCustomer-> statusReq == 5)
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</li>
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</li>
    @if ($purchaseCustomer-> type == 'شراء') 
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</li>
    @elseif ($purchaseCustomer-> type == 'رهن')
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}</li>
    @endif
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }}</li>

    @elseif ($purchaseCustomer-> statusReq == 6 || $purchaseCustomer-> statusReq == 7 || $purchaseCustomer-> statusReq == 8 || $purchaseCustomer-> statusReq == 9 || $purchaseCustomer-> statusReq == 10)
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</li>
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</li>
    @if ($purchaseCustomer-> type == 'شراء') 
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</li>
    @elseif ($purchaseCustomer-> type == 'رهن')
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}</li>
    @endif
    <li>{{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }}</li>

    @elseif ($purchaseCustomer-> statusReq == 11 || $purchaseCustomer-> statusReq == 12 || $purchaseCustomer-> statusReq == 13 ||  $purchaseCustomer-> statusReq == 14 ||  $purchaseCustomer-> statusReq == 15 || $purchaseCustomer-> statusReq == 16)
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Agent') }}</li>
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Sales Manager') }}</li>
    @if ($purchaseCustomer-> type == 'شراء') 
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Funding Manager') }}</li>
    @elseif ($purchaseCustomer-> type == 'رهن')
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'Mortgage Manager') }}</li>
    @endif
    <li class="active">{{ MyHelpers::admin_trans(auth()->user()->id,'General Manager') }}</li>


    @endif

</ul>

<br><br>

@endif


<!--
    Request statues:

0 : new
1 : open
2 : archived in sales agent
3 : waiting for sales manager approval
4 : rejected from sales manager
5 : archived in sales manager

Then request will divide based on request type (funding,mortgage)

for funding type:
6 : waiting for funding manager approval
7 : rejected from funding manager
8 : archived in funding manager

for mortgage type:
9 : waiting for mortgage manager approval
10 : rejected from mortgage manager
11 : archived in mortgage manager

then all request will move to general manager:
12 : waiting for general manager approval
13 : archived in general manager
14 : canceled
15 : completed

-->