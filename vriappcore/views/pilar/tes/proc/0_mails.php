<h3>BANDEJA DE TESISTA</h3>
<div clas="table-responsive" style="overflow-x:auto;">
<table class="table table-response table-striped table-hover" width="100%">
  <thead>
    <tr class="text-primary">
        <td width='2%' class="inbox-small-cells"><i class="glyphicon glyphicon-envelope"></i></td>
        <td width='10%'class="view-message">Desde </td>
        <td width='15%'class="view-message">Asunto</td>
        <td width='60%'class="view-message ">Mensaje</td>
        <td width='15%'class="view-message  text-right">Fecha</td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($prev->result() as $row) {
    ?>
    <tr>
        <td class="view-message "><i class="glyphicon glyphicon-envelope"></i></td>
        <td class="inbox-small-cells">PILAR</td>
        <td class="view-message  dont-show"><b><?=$row->Titulo;?></b></td>
        <td class="view-message "><?=$row->Mensaje;?></td>
        <td class="view-message  text-right"><?=$row->Fecha;?></td>
    </tr>
    <?php
    } ?>
  </tbody>
</table>
</div>