<!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <style type="text/css">
        body{background-color: #DDD;}
        #top{
            height:250px;
            background-color: #1db084;
            color:white;
        }
        .title{
            padding:78px 18px 0px 18px ;
            float: left;
            font-size:46px;
            font-family:微软雅黑,黑体;
        }
        .logo{padding:50px;
            float: right;}
        #WelcomeInfo{
            padding:8px;
            font-size:20px;
            font-family:微软雅黑,黑体;
        }
        .headnav{
        text-decoration:none;
        color:#1db084;
        font-size:24px;
        font-family: MS PGothic,黑体;
        text-align:center
    }
    </style>
</head>
<body>
<!--公告部分-->
<div id="tips" style="color:white;background-color:  #34495e;width:100%;height:160px;>
<div  style="color:white;width:980px;margin:0 auto;border:3px solid #000;padding: 3px 3px 3px 3px;">
<h3>闭站公告</h3>
&nbsp;&nbsp;&nbsp;&nbsp;亲爱的同学，一年的时光已经过去了，步入大二的我们将回归原班级上课，鉴于专业方向的不同课件站已经没有继续存在的意义了。故决定本站于2017年7月16日正式关闭，届时本站所有课件将会打包整理成压缩文件上传至班级群文件共享方便大家下载。本站源代码将托管至凩茻MUMU的Github仓库，欢迎感兴趣的同学后续开发维护。微信公众平台将一如既往地为大家提供班级信息资讯通知服务。感谢大家长期以来的支持！<br><span style="float:right">凩茻MUMU</span><br><span style="float:right">2017年6月30日</span>
</div>
</div>
<!--公告部分END-->
<script language="javascript">
function codefans(){
var box=document.getElementById("tips");//还用说？这里的参数必须和div的id对应起来啊
box.style.display="none";
}
setTimeout("codefans()",15000);//15秒，可以改动
</script>

<div  id="top">
<div class="title">
北科天院信实1601课件下载站
<div id="WelcomeInfo">
欢迎同学们前来下载课件。<br />
Welcome to download the courseware.
</div>
</div>
<div class="logo">
<img src="./indexfiles/images/classLogo.png" alt="Logo" width="150px"; height="150px">
</div>
</div>
<!-- 这是导航栏-->
<div style="background-color:white;">
<table style="text-align:center; width:100%;">
<tr>
<th><a href="http://mumu0934.cn" class="headnav">Home</a></th>
<th><a href="http://mumu0934.cn/SchoolNav.html" class="headnav">Navigation</a></th>
<th><a href="http://mumu0934.cn/kejian/index.php" class="headnav">Download</a></th>
<th><a href="https://mumu0934.github.io/about/" class="headnav">About</a></th>
</tr>
</table>
</div>
<!--导航栏END-->



</body>
</html>