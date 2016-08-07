<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript"  src="Concurrent.Thread.js"></script>
        <link rel="stylesheet" href="css/bootstrap-3.3.6-dist/css/bootstrap.min.css" type="text/css">
        <title></title>

    </head>
    <body >
        <?php
        include_once ("../Modelo/Calculos.php");
        $calculos = new Calculos();
        $ee = $calculos->eficienciaEspectral();
        $numUsuarios = $calculos->usuariosSecundariosXCanal();
        echo '<h1> Eficiencia Espectral </h1>';
        echo '<table>';
        echo '<tr>';
        echo '<th>canal</th>';
        echo '<th>eficiencia</th>';
        echo '</tr>';
        foreach ($ee as $key => $val) {
            echo '<tr>';
            echo '<td>' . $key . '</td>';
            echo '<td>' . $val . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<h1> Usuarios Secundarios por canal </h1>';
        echo '<table>';
        echo '<tr>';
        echo '<th>canal</th>';
        echo '<th>Número de usuarios</th>';
        echo '</tr>';
        foreach ($numUsuarios as $key => $val) {
            echo '<tr>';
            echo '<td>' . $key . '</td>';
            echo '<td>' . $val . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        ?>
    </body>
</html>
