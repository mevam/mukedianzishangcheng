<?php
/**
 * 检查管理员是否存在
 * Created by PhpStorm.
 * User: tete
 * Date: 2017/2/11
 * Time: 14:34
 */
require_once '../lib/mysql.func.php';
function checkAdmin($sql){
    return fetchOne($sql);
}
//检查是否有管理员登录
function checkLogined(){
    if ($_SESSION['adminId']=="" && $_COOKIE['adminId']==""){
        alertMes("请先登录","login.php");
    }
}
//注销管理员
function logout(){
    $_SESSION=array();
    if (isset($_COOKIE[session_name()])){
        setcookie(session_name(),"",time()-1);
    }
    if(isset($_COOKIE['adminId'])){
        setcookie("adminId","",time()-1);
    }
    if (isset($_COOKIE['adminName'])){
        setcookie("adminName","",time()-1);
    }
    session_destroy();
    header("location:login.php");
}
//添加管理员
function addAdmin()
{
    $arr = $_POST;
    $arr['password']=md5($_POST['password']);
    if (insert("imooc_admin", $arr)) {
        $mes = "添加成功！<br/><a href='addAdmin.php'>继续添加</a>|<a href='listAdmin.php'>查看管理员列表</a>";
    } else {
        $mes = "添加失败!<br/><a href='addAdmin.php'>重新添加</a>";
    }
    return $mes;
}
//得到管理员列表
function getAllAdmin(){
    $sql="select id,username,email from imooc_admin";
    $rows=fetchAll($sql);
    return $rows;
}
//编辑管理员
function editAdmin($id){
    $arr=$_POST;
    $arr['password']=md5($_POST['password']);
    if (update("imooc_admin",$arr,"id={$id}")){
        $mes="编辑成功!<a href='listAdmin.php'>查看管理员列表</a>";

    }else{
        $mes="编辑失败!<a href='listAdmin.php'>请重新修改</a>";
    }
    return $mes;
}
//删除管理员
function delAdmin($id){
    if(delete("imooc_admin","id={$id}")){
        $mes="删除成功!<br/><a href='listAdmin.php'>查看管理员列表</a>";
    }else{
        $mes="删除失败!<br/><a href='listAdmin.php'>请重新删除</a>";
    }
    return $mes;
}
/*
function getAdminByPage($pageSize=2){
    $sql="select * from imooc_admin";
    $totalRows=getResultNum($sql);
    global $totalPage;
    $totalPage=ceil($totalRows/$pageSize);
    $page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
    if ($page<1 || $page==null || !is_numeric($page)){
        $page=1;
    }
    if ($page>=$totalPage) $page=$totalPage;
    $offset=($page-1)*$pageSize;
    $sql="select id,username,email from imooc_admin limit {$offset},{$pageSize}";
    $rows=fetchAll($sql);
    return $rows;
}
*/