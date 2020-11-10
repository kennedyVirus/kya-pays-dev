window.onload = function () {
    var app = new Vue({
        delimiters: ['${', '}'],
        el: '#app',
        data: {
            new_enterprise:{
                email:'',
                phone_number:'',
                transaction_phone_number:'',
                first_name:'',
                last_name:'',
                address:'',
                country:'',
                city:'',
                job_title:'',
                organisation:'',
                amount_category:'',
                type:1
            },
            new_academic:{
                email:'',
                phone_number:'',
                transaction_phone_number:'',
                first_name:'',
                last_name:'',
                address:'',
                country:'',
                city:'',
                job_title:'',
                organisation:'',
                amount_category:'',
                type:2
            },
            new_student:{
                email:'',
                phone_number:'',
                transaction_phone_number:'',
                first_name:'',
                last_name:'',
                address:'',
                country:'',
                city:'',
                job_title:'',
                organisation:'',
                amount_category:'',
                type:3
            },
            recover_email:'',
            recover_transaction_ref:'',
            code_to_unlock_key:'',
            phone_number_to_unlock_key:'',
            countries:'',
            is_card_check:false,
            showTransactionPhoneInputEnterprise:false,
            showTransactionPhoneInputAcademic:false,
            showTransactionPhoneInputStudent:false
        },

        mounted: function (){

            axios.get('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/rest/country/get')
                .then((response)=>{
                    console.log(response.data)
                    console.log(response.data.data)
                    if(response.data.error===0){
                        this.countries=response.data.data
                        //
                        if(this.countries.length >0){
                            this.countries.forEach((country,i)=>{
                                var countr = "<option value=\""+ i +"\" >"+ country.name +"</option>\n";
                                $("#country33").after(countr);
                            })
                        }
                        //

                        }
                    })


            //integer value validation
            $('input.floatNumber').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');
            });

            $( "#check_mobile_money_enterprise" ).change(function() {
                if(this.checked){
                    $( "#check_card_enterprise" ).prop('checked',false)
                    app.showTransactionPhoneInputEnterprise=true
                }else{
                    $( "#check_card_enterprise" ).prop('checked',true)
                    app.showTransactionPhoneInputEnterprise=false
                }
            });

            $( "#check_card_enterprise" ).change(function() {
                if(this.checked){
                    $( "#check_mobile_money_enterprise" ).prop('checked',false)
                    app.showTransactionPhoneInputEnterprise=false
                }else{
                    $( "#check_mobile_money_enterprise" ).prop('checked',true)
                    app.showTransactionPhoneInputEnterprise=true
                }
            });

            $( "#check_mobile_money_academic" ).change(function() {
                if(this.checked){
                    $( "#check_card_academic" ).prop('checked',false)
                    app.showTransactionPhoneInputAcademic=true
                }else{
                    $( "#check_card_academic" ).prop('checked',true)
                    app.showTransactionPhoneInputAcademic=false

                }
            });

            $( "#check_card_academic" ).change(function() {
                if(this.checked){
                    $( "#check_mobile_money_academic" ).prop('checked',false)
                    app.showTransactionPhoneInputAcademic=false
                }else{
                    $( "#check_mobile_money_academic" ).prop('checked',true)
                    app.showTransactionPhoneInputAcademic=true
                }
            });

            $( "#check_mobile_money_student" ).change(function() {
                if(this.checked){
                    $( "#check_card_student" ).prop('checked',false)
                    app.showTransactionPhoneInputStudent=true
                }else{
                    $( "#check_card_student" ).prop('checked',true)
                    app.showTransactionPhoneInputStudent=false

                }
            });

            $( "#check_card_student" ).change(function() {
                if(this.checked){
                    $( "#check_mobile_money_student" ).prop('checked',false)
                    app.showTransactionPhoneInputStudent=false
                }else{
                    $( "#check_mobile_money_student" ).prop('checked',true)
                    app.showTransactionPhoneInputStudent=true
                }
            });
        },

        methods : {
            openPay1Modal(){
                $('#enterpriseModal').modal('show')
            },
            openPay2Modal(){
                $('#academicModal').modal('show')
            },
            openPay3Modal(){
                $('#studentModal').modal('show')
            },
            // openRecoverModal(){
            //     $('#modal_recover_key').modal('open')
            // },
            // recoverKey(){
            //     if(this.recover_email!='' || this.recover_transaction_ref!=''){
            //
            //         let data={
            //             'email':this.recover_email,
            //             'transaction_ref':this.transaction_ref,
            //         }
            //         $('#modal-loader').modal('open');
            //
            //         Swal.fire({
            //             title: 'Desolé!',
            //             text: "Aucun résultat ne correspond à votre recherche",
            //             icon: 'warning',
            //             confirmButtonText: 'OK'
            //         })
            //         // Swal.fire({
            //         //     title: 'Error!',
            //         //     text: 'Problème de connexion , actualiser la page svp',
            //         //     icon: 'error',
            //         //     confirmButtonText: 'OK'
            //         // })
            //
            //         // Swal.fire({
            //         //     title: 'Opération réussie',
            //         //     text: message,
            //         //     icon: 'success',
            //         //     confirmButtonText: 'OK'
            //         // });
            //
            //         // axios.post('/fflll',data)
            //         //     .then((response)=>{
            //         //         $('#modal-loader').modal('close');
            //         //
            //         //        // if(response.data.error===0){
            //         //             Swal.fire({
            //         //                 title: 'Desolé!',
            //         //                 text: "Aucun résultat ne correspond à votre recherche",
            //         //                 icon: 'warning',
            //         //                 confirmButtonText: 'OK'
            //         //             })
            //         //       //  }
            //         //
            //         //     })
            //
            //
            //
            //
            //     }
            //
            // },
            //
            // studentStep1(){
            //     $('#modal_pay_student1').modal('open')
            // },
            // studentStep2(){
            //     $('#modal_pay_student1').modal('close')
            //     $('#modal_pay_student2').modal('open')
            // },
            // enterpriseStep1(){
            //     $('#modal_pay_enterprise1').modal('open')
            // },
            // enterpriseStep2(){
            //     $('#modal_pay_enterprise1').modal('close')
            //     $('#modal_pay_enterprise2').modal('open')
            // },

            submitAcademicForm(){
                console.log('academic...')
                var checked = false

                let selected = '';

                for (let i = 1;  i < 7 ; i++) {

                    if($('#academic'+i).is(':checked')) {
                        checked = true;
                        selected = i;
                        break;
                    }
                }
                if (checked === true) {
                    this.new_academic.amount_category=selected


                    if(this.showTransactionPhoneInputAcademic==true){
                        let is_error=false
                        let error_message=''
                        if(this.new_academic.transaction_phone_number=='' ||  this.new_academic.transaction_phone_number===''){
                            is_error=true;
                            error_message='Vous avez choisi de payer via mobile money.veuillez entrer un numéro de téléphone togolais pour continuer l\'opération.Merci';
                        }

                        if(this.new_academic.transaction_phone_number.length<8){
                            is_error=true;
                            error_message='Veuillez entrer un numéro de téléphone togolais de 8 chiffres pour continuer l\'opération.Merci';
                        }

                        if(is_error==true){
                            Swal.fire({
                                title: 'Erreur Numéro de télephone!',
                                text: error_message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }else {
                            axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paygate/payment/init',this.new_academic)
                                .then((response)=>{
                                    $('#modal-loader').modal('hide');

                                    $('#academicModal').modal('hide')

                                    //console.log(response.data)
                                    if(response.data.error===0){
                                        if(response.data.data.type===1){
                                            Swal.fire({
                                                title: 'Confirmation!',
                                                text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                                                icon: 'warning',
                                                confirmButtonText: 'Continuer'
                                            }).then((result) => {
                                                if (result.value) {
                                                    window.location.href = response.data.data.url
                                                }
                                            })}
                                        else {
                                            Swal.fire({
                                                title: 'Confirmation!',
                                                text: "Veuillez consulter votre messagerie pour continuer l'opération",
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            })
                                        }
                                    }else{
                                        if(response.data.error===-2){
                                            Swal.fire({
                                                title: 'Erreur Numéro de téléphone!',
                                                text: 'Veuillez entrer un numéro de téléphone togolais',
                                                icon: 'error',
                                                confirmButtonText: 'J\'ai compris'
                                            })
                                        }else {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Oups.Une erreur est survenue , réssayez svp',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            })
                                        }
                                    }
                                }).catch((error)=>{
                            })
                        }
                    }
                    else {
                        axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paydunya/payment/init',this.new_academic)
                            .then((response)=>{
                                $('#modal-loader').modal('hide');

                                $('#enterpriseModal').modal('hide')

                              //  console.log(response.data)
                                if(response.data.error===0){

                                    if(response.data.data.type===1){
                                        Swal.fire({
                                            title: 'Confirmation!',
                                            text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                                            icon: 'warning',
                                            confirmButtonText: 'Continuer'
                                        }).then((result) => {
                                            if (result.value) {
                                                window.location.href = response.data.data.url
                                            }
                                        })
                                    }else {
                                        Swal.fire({
                                            title: 'Erreur Transaction!',
                                            text: 'Une erreur est survenue lors de votre transaction.Veuillez réssayer svp',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        })
                                    }
                                }else{
                                        Swal.fire({
                                            title: 'Erreur!',
                                            text: 'Oups.Une erreur est survenue , réssayez svp',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        })
                                }
                            }).catch((error)=>{
                        })
                    }
                }
            },
            submitStudentForm(){

                console.log('studennt....')
                var checked = false

                let selected = '';

                for (let i = 1;  i < 4 ; i++) {

                    if($('#student'+i).is(':checked')) {
                        checked = true;
                        selected = i;
                        break;
                    }
                }
                if (checked === true) {

                    this.new_student.amount_category=selected

                   // console.log(this.new_student)

                    if(this.showTransactionPhoneInputStudent==true){
                        let is_error=false
                        let error_message=''
                        if(this.new_student.transaction_phone_number=='' ||  this.new_student.transaction_phone_number===''){
                            is_error=true;
                            error_message='Vous avez choisi de payer via mobile money.veuillez entrer un numéro de téléphone togolais pour continuer l\'opération.Merci';
                        }

                        if(this.new_student.transaction_phone_number.length<8){
                            is_error=true;
                            error_message='Veuillez entrer un numéro de téléphone togolais de 8 chiffres pour continuer l\'opération.Merci';
                        }

                        if(is_error==true){
                            Swal.fire({
                                title: 'Erreur Numéro de télephone!',
                                text: error_message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }else {

                            axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paygate/payment/init',this.new_student)
                                .then((response)=>{
                                    $('#modal-loader').modal('hide');

                                    $('#studentModal').modal('hide')

                                  //  console.log(response.data)
                                    if(response.data.error===0){
                                        if(response.data.data.type===1){
                                            Swal.fire({
                                                title: 'Confirmation!',
                                                text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                                                icon: 'warning',
                                                confirmButtonText: 'Continuer'
                                            }).then((result) => {
                                                if (result.value) {
                                                    window.location.href = response.data.data.url
                                                }
                                            })}
                                        else {
                                            Swal.fire({
                                                title: 'Confirmation!',
                                                text: "Veuillez consulter votre messagerie pour continuer l'opération",
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            })
                                        }
                                    }else{
                                        if(response.data.error===-2){
                                            Swal.fire({
                                                title: 'Erreur Numéro de téléphone!',
                                                text: 'Veuillez entrer un numéro de téléphone togolais',
                                                icon: 'error',
                                                confirmButtonText: 'J\'ai compris'
                                            })
                                        }else {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Oups.Une erreur est survenue , réssayez svp',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            })
                                        }
                                    }
                                }).catch((error)=>{
                            })
                        }

                    }
                    else {
                        axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paydunya/payment/init',this.new_student)
                            .then((response)=>{
                                $('#modal-loader').modal('hide');

                                $('#enterpriseModal').modal('hide')

                                //console.log(response.data)
                                if(response.data.error===0){

                                    if(response.data.data.type===1){
                                        Swal.fire({
                                            title: 'Confirmation!',
                                            text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                                            icon: 'warning',
                                            confirmButtonText: 'Continuer'
                                        }).then((result) => {
                                            if (result.value) {
                                                window.location.href = response.data.data.url
                                            }
                                        })
                                    }else {
                                        Swal.fire({
                                            title: 'Erreur Transaction!',
                                            text: 'Une erreur est survenue lors de votre transaction.Veuillez réssayer svp',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        })
                                    }
                                }else{
                                    Swal.fire({
                                        title: 'Erreur!',
                                        text: 'Oups.Une erreur est survenue , réssayez svp',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    })
                                }
                            }).catch((error)=>{
                        })
                    }
                }
            },
            submitEnterpriseForm(){

                console.log('enterprise....')
                var checked = false

                let selected = '';

                for (let i = 1;  i < 7 ; i++) {

                    if($('#enterprise'+i).is(':checked')) {
                        checked = true;
                        selected = i;
                        break;
                    }
                }
                if (checked === true) {
                    this.new_enterprise.amount_category=selected

                    //console.log(this.new_enterprise)

                    if(this.showTransactionPhoneInputEnterprise==true){
                        let is_error=false
                        let error_message=''
                        if(this.new_enterprise.transaction_phone_number=='' ||  this.new_enterprise.transaction_phone_number===''){
                            is_error=true;
                            error_message='Vous avez choisi de payer via mobile money.veuillez entrer un numéro de téléphone togolais pour continuer l\'opération.Merci';
                        }

                        if(this.new_enterprise.transaction_phone_number.length<8){
                            is_error=true;
                            error_message='Veuillez entrer un numéro de téléphone togolais de 8 chiffres pour continuer l\'opération.Merci';
                        }

                        if(is_error==true){
                            Swal.fire({
                                title: 'Erreur Numéro de télephone!',
                                text: error_message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }else {

                            axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paygate/payment/init',this.new_enterprise)
                                .then((response)=>{
                                    $('#modal-loader').modal('hide');

                                    $('#studentModal').modal('hide')

                                   // console.log(response.data)
                                    if(response.data.error===0){
                                        if(response.data.data.type===1){
                                            Swal.fire({
                                                title: 'Confirmation!',
                                                text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                                                icon: 'warning',
                                                confirmButtonText: 'Continuer'
                                            }).then((result) => {
                                                if (result.value) {
                                                    window.location.href = response.data.data.url
                                                }
                                            })}
                                        else {
                                            Swal.fire({
                                                title: 'Confirmation!',
                                                text: "Veuillez consulter votre messagerie pour continuer l'opération",
                                                icon: 'warning',
                                                confirmButtonText: 'OK'
                                            })
                                        }
                                    }else{
                                        if(response.data.error===-2){
                                            Swal.fire({
                                                title: 'Erreur Numéro de téléphone!',
                                                text: 'Veuillez entrer un numéro de téléphone togolais',
                                                icon: 'error',
                                                confirmButtonText: 'J\'ai compris'
                                            })
                                        }else {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Oups.Une erreur est survenue , réssayez svp',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            })
                                        }
                                    }
                                }).catch((error)=>{
                            })
                        }

                    }
                    else {
                        axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paydunya/payment/init',this.new_enterprise)
                            .then((response)=>{
                                $('#modal-loader').modal('hide');

                                $('#enterpriseModal').modal('hide')

                               // console.log(response.data)
                                if(response.data.error===0){

                                    if(response.data.data.type===1){
                                        Swal.fire({
                                            title: 'Confirmation!',
                                            text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                                            icon: 'warning',
                                            confirmButtonText: 'Continuer'
                                        }).then((result) => {
                                            if (result.value) {
                                                window.location.href = response.data.data.url
                                            }
                                        })
                                    }else {
                                        Swal.fire({
                                            title: 'Erreur Transaction!',
                                            text: 'Une erreur est survenue lors de votre transaction.Veuillez réssayer svp',
                                            icon: 'error',
                                            confirmButtonText: 'OK'
                                        })
                                    }
                                }else{
                                    Swal.fire({
                                        title: 'Erreur!',
                                        text: 'Oups.Une erreur est survenue , réssayez svp',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    })
                                }
                            }).catch((error)=>{
                        })
                    }

                    // if(this.showTransactionPhoneInputEnterprise==true){
                    //     let is_error=false
                    //     let error_message=''
                    //     if(this.new_enterprise.transaction_phone_number=='' ||  this.new_enterprise.transaction_phone_number===''){
                    //         is_error=true;
                    //         error_message='Vous avez choisi de payer via mobile money.veuillez entrer un numéro de téléphone togolais pour continuer l\'opération.Merci';
                    //     }
                    //
                    //     if(this.new_enterprise.transaction_phone_number.length<8){
                    //         is_error=true;
                    //         error_message='Veuillez entrer un numéro de téléphone togolais de 8 chiffres pour continuer l\'opération.Merci';
                    //     }
                    //
                    //     if(is_error==true){
                    //         Swal.fire({
                    //             title: 'Erreur Numéro de télephone!',
                    //             text: error_message,
                    //             icon: 'error',
                    //             confirmButtonText: 'OK'
                    //         })
                    //     }else {
                    //         this.new_enterprise.amount_category=selected
                    //
                    //         $('#modal-loader').modal('show');
                    //         console.log(this.new_enterprise)
                    //         axios.post('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/paydunya/payment/init',this.new_enterprise)
                    //             .then((response)=>{
                    //                 $('#modal-loader').modal('hide');
                    //
                    //                 $('#enterpriseModal').modal('hide')
                    //
                    //                 console.log(response.data)
                    //                 if(response.data.error===0){
                    //
                    //                     if(response.data.data.type===1){
                    //                         Swal.fire({
                    //                             title: 'Confirmation!',
                    //                             text: "Vous serez redirigé vers un site marchand pour continuer l'opération",
                    //                             icon: 'warning',
                    //                             confirmButtonText: 'Continuer'
                    //                         }).then((result) => {
                    //                             if (result.value) {
                    //                                 window.location.href = response.data.data.url
                    //                             }
                    //                         })
                    //                     }else {
                    //                         Swal.fire({
                    //                             title: 'Erreur Transaction!',
                    //                             text: 'Une erreur est survenue lors de votre transaction.Veuillez réssayer svp',
                    //                             icon: 'error',
                    //                             confirmButtonText: 'OK'
                    //                         })
                    //                     }
                    //                 }else{
                    //                     if(response.data.error===-2){
                    //                         Swal.fire({
                    //                             title: 'Erreur Numéro de téléphone!',
                    //                             text: 'Veuillez entrer un numéro de téléphone togolais',
                    //                             icon: 'error',
                    //                             confirmButtonText: 'J\'ai compris'
                    //                         })
                    //                     }else {
                    //                         Swal.fire({
                    //                             title: 'Error!',
                    //                             text: 'Oups.Une erreur est survenue , réssayez svp',
                    //                             icon: 'error',
                    //                             confirmButtonText: 'OK'
                    //                         })
                    //                     }
                    //                 }
                    //             }).catch((error)=>{
                    //             console.log(error)
                    //         })
                    //     }
                    //
                    // }
                }
            },

            getLicenceKey(){
                let code=$('#code_to_unlock_key').val()
                let phone_number=$('#phone_number_to_unlock_key').val()

                let error=false
                let message=''

                if(code=='' ||  code==='') {
                    error=true
                 //   message='Veuillez entrer un code'
                }
                if(phone_number=='' ||  phone_number==='') {
                    error=true
                   // message='Veuillez entrer un Numéro de téléphone'
                }

                if(error==true) {
                    Swal.fire({
                        title: 'Erreur!',
                        text: 'Veuillez entrer un code et un numéro de téléphone pour continuer',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }else {
                    this.code_to_unlock_key=code
                    this.phone_number_to_unlock_key=phone_number
                    let data={
                        'code':this.code_to_unlock_key,
                        'phone_number':this.phone_number_to_unlock_key
                    }
                    axios.post('/8004064b17546e4380ce83d1be75b50dkfj/api/kya/sol/design/unlock/key',data)
                        .then((response)=>{
                            if(response.data.error===0){
                                Swal.fire({
                                    title: 'Bravo.Voici votre clé d\'activation KYA-SolDesign',
                                    text: response.data.data.key,
                                    icon: 'success',
                                  //  confirmButtonText: 'OK'
                                })
                            }else {
                                Swal.fire({
                                    title: 'Oups!',
                                    text: 'Aucune clé d\'activation trouvée .Rééssayéz svp!',
                                    icon: 'error',
                                    confirmButtonText: 'J\'ai compris'
                                })
                            }
                        }).catch((error)=>{

                    })

                }


                    // document.getElementById("loader").style.display = "block";
            }

        },
        filters:{
        }
    })
}