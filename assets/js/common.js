const base_url = $('body').attr('data-url');
const token = $('body').attr('data-token');

function load_selectBox(column,table,where,selectBox,selected='',groupby=''){
    $.ajax({
        url:base_url + 'users/load_selectBox',
        method:"POST",
        data:{token:token,table:table,where:JSON.stringify(where),column:column,groupby:groupby},
        success:function(res){
           
            $(selectBox).html("");
            res = $.parseJSON(res);
            if(res.status){
                data = res.data;
                if(data){
                    var option = "<option selected disabled value=''>Select</option>";
                    $.each(data,function(index,value){
                        if(selected!='' && selected == value.id){
                            option += "<option selected value='"+value.id+"'>"+value.name+"</option>";
                        }else{
                            option += "<option value='"+value.id+"'>"+value.name+"</option>";
    
                        }  
                    })
                    
                    $(selectBox).append(option);
                    
                }
            }
        },error:function(err){
            console.log(err);
        }
    })
}