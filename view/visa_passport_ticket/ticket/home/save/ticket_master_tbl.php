 <tr>
    <td><input class="css-checkbox" id="chk_ticket1" type="checkbox" checked><label class="css-label" for="chk_ticket1"> <label></td>
    <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
    <td><input type="text" id="first_name1" name="first_name" onchange="fname_validate(this.id)" placeholder="*First Name" title="First Name"/></td>
    <td><input type="text" id="middle_name1" name="middle_name" onchange="fname_validate(this.id)" placeholder="Middle Name" title="Middle Name"/></td>
    <td><input type="text" id="last_name1" name="last_name" onchange="fname_validate(this.id)" placeholder="Last Name" title="Last Name"/></td> 
    <td><input type="text" id="birth_date1" name="birth_date" class="app_datepicker" placeholder="DOB" title="DOB" onchange="adolescence_reflect(this.id)" value="<?= date('d-m-Y',  strtotime(' -1 day'))?>"/></td>    
    <td><input type="text" id="adolescence1" name="adolescence" placeholder="*Adolescence" title="Adolescence" disabled/></td>
    <td><input type="text" id="ticket_no1" style="text-transform: uppercase;" onchange="validate_spaces(this.id)" name="ticket_no" placeholder="Ticket No" title="Ticket No"/></td>
    <td><input type="text" id="gds_pnr1" name="gds_pnr" style="text-transform: uppercase;" onchange="validate_spaces(this.id)" placeholder="GDS PNR" title="GDS PNR"></td>
</tr>


<script>

var date = new Date();
var yest = date.setDate(date.getDate()-1);
 
$('#birth_date1').datetimepicker({ timepicker:false, maxDate:yest, format:'d-m-Y' });

</script>