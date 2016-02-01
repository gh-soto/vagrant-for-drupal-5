<?php
if(isset($_SESSION['fillFields'])){
                foreach($_SESSION['fillFields'] as $key=>$value){
                $fieldValues[$key] .= "value=\"$value\"";
        }
}
if(isset($_SESSION['errorFields']['fields'])){
        foreach($_SESSION['errorFields']['fields'] as $key=>$value){
                $fieldValues[$key] .= " class=\"form-text error\" ";
        }
}
if(isset($_SESSION['errorFields'])){
        echo '<div id="message"><div class="messages error"><ul>';
        echo "<li>Required fields are unfilled</li>";
}
if(isset($_SESSION['errorFields']['email'])){
        $fieldValues['email'] .= " value=\"".$_SESSION['fillFields']['email']."\"class=\"form-text error\" ";
        echo "<li>".$_SESSION['errorFields']['email']."</li>";
}
if(isset($_SESSION['errorFields']['username'])){
		$fieldValues['username'] .= " value=\"".$_SESSION['fillFields']['username']."\"class=\"form-text error\" ";
		echo "<li>".$_SESSION['errorFields']['username']."</li>";
}
if(isset($_SESSION['errorFields']['password'])){
        $fieldValues['password'] .= " class=\"form-text error\" ";
        echo "<li>".$_SESSION['errorFields']['password']."</li>";
}
if(isset($_SESSION['errorFields'])){
        echo '</ul></div></div>';
}
?>