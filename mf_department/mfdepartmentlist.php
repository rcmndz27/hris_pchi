<?php

Class MfdepartmentList{

    public function GetAllMfdepartmentList(){
        global $connL;

        echo '<table id="allMfdepartmentList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Department Code</th>
                <th>Department Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_dept ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $cde = "'".$result['code']."'";
                echo '
                <tr>
                <td id="dc'.$result['rowid'].'">' . $result['code']. '</td>
                <td id="dn'.$result['rowid'].'">' . $result['descs']. '</td>
                <td id="st'.$result['rowid'].'">' . $result['status']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfdepartmentModal('.$rowd.','.$cde.')">
                                <i class="fas fa-edit"></i> Update
                            </button></td>';
                
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>
        ';
    }
}

?>

