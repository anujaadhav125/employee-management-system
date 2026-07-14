</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>

document.addEventListener("DOMContentLoaded",function(){

const department=document.getElementById("department");

if(department){

department.addEventListener("change",function(){

fetch("../ajax/get_designations.php?department_id="+this.value)

.then(response=>response.text())

.then(data=>{

document.getElementById("designation").innerHTML=data;

});

});

}

});

</script>
</body>
</html>