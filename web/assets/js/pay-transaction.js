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
                pay_method:1,
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
                pay_method:1,
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
                pay_method:1,
                type:3
            },
            recover_email:'',
            recover_transaction_ref:'',
            code_to_unlock_key:'',
            phone_number_to_unlock_key:'',
            countries:'',
            selected_country:0,
            selected_payment_method:0,
            is_card_check:false,
            showTransactionPhoneInputEnterprise:false,
            showTransactionPhoneInputAcademic:false,
            showTransactionPhoneInputStudent:false,
            togo_selected:false,
            benin_selected:false,
            cote_ivoire_selected:false,
            senegal_selected:false
        },

        mounted: function (){
            // $('.selectpicker').selectpicker();
            //
            axios.get('/8004064b17546e4380ce83d1be75b50dkfj2015/api/kya/rest/country/get')
                .then((response)=>{
                    if(response.data.error===0){
                        this.countries=response.data.data

                        if(this.countries.length >0){
                            this.countries.forEach((country,i)=>{

                                 $('#country_student_select').append(new Option(country.name, i));
                                 $('#country_academic_select').append(new Option(country.name, i));
                                 $('#country_enterprise_select').append(new Option(country.name, i));

                            })
                        }

                    }
                }).catch((error)=>{})

            $("select#country_student_select").change(function(){
                let selectedCountry = $(this).children("option:selected").val();
                app.selected_country=selectedCountry

                switch (parseInt(app.selected_country)){
                    case  226:
                        app.togo_selected=true
                        app.benin_selected=false
                        app.cote_ivoire_selected=false
                        app.senegal_selected=false

                        if(this.selected_payment_method===0){
                            app.showTransactionPhoneInputStudent=true
                        }else {
                            app.showTransactionPhoneInputStudent=false
                        }
                        break;

                    case 23:
                        app.togo_selected=false
                        app.benin_selected=true
                        app.cote_ivoire_selected=false
                        app.senegal_selected=false
                        app.showTransactionPhoneInputStudent=false

                        break;

                    case 106:
                        app.togo_selected=false
                        app.benin_selected=false
                        app.cote_ivoire_selected=true
                        app.senegal_selected=false
                        app.showTransactionPhoneInputStudent=false

                        break;

                    case 198:
                        app.togo_selected=false
                        app.benin_selected=false
                        app.cote_ivoire_selected=false
                        app.senegal_selected=true
                        app.showTransactionPhoneInputStudent=false

                        break;
                }
            });
            $("select#country_academic_select").change(function(){
                let selectedCountry = $(this).children("option:selected").val();
                app.selected_country=selectedCountry

                switch (parseInt(app.selected_country)){
                    case 226:
                        app.togo_selected=true
                        app.benin_selected=false
                        app.cote_ivoire_selected=false
                        app.senegal_selected=false

                        if(this.selected_payment_method===0){
                            app.showTransactionPhoneInputAcademic=true
                        }else {
                            app.showTransactionPhoneInputAcademic=false
                        }
                        break;
                    case 23:
                        app.togo_selected=false
                        app.benin_selected=true
                        app.cote_ivoire_selected=false
                        app.senegal_selected=false
                        app.showTransactionPhoneInputAcademic=false

                        break;
                    case 106:
                        app.togo_selected=false
                        app.benin_selected=false
                        app.cote_ivoire_selected=true
                        app.senegal_selected=false
                        app.showTransactionPhoneInputAcademic=false

                        break;
                    case 198:
                        app.togo_selected=false
                        app.benin_selected=false
                        app.cote_ivoire_selected=false
                        app.senegal_selected=true
                        app.showTransactionPhoneInputAcademic=false

                        break;
                }
            });
            $("select#country_enterprise_select").change(function(){
                let selectedCountry = $(this).children("option:selected").val();
                app.selected_country=selectedCountry

                switch (parseInt(app.selected_country)){
                    case  226:
                        app.togo_selected=true
                        app.benin_selected=false
                        app.cote_ivoire_selected=false
                        app.senegal_selected=false

                        if(this.selected_payment_method===0){
                            app.showTransactionPhoneInputEnterprise=true
                        }else {
                            app.showTransactionPhoneInputEnterprise=false
                        }
                        break;
                    case 23:
                        app.togo_selected=false
                        app.benin_selected=true
                        app.cote_ivoire_selected=false
                        app.senegal_selected=false
                        app.showTransactionPhoneInputEnterprise=false

                        break;
                    case 106:
                        app.togo_selected=false
                        app.benin_selected=false
                        app.cote_ivoire_selected=true
                        app.senegal_selected=false
                        app.showTransactionPhoneInputEnterprise=false

                        break;
                    case 198:
                        app.togo_selected=false
                        app.benin_selected=false
                        app.cote_ivoire_selected=false
                        app.senegal_selected=true
                        app.showTransactionPhoneInputEnterprise=false

                        break;
                }
            });


            //integer value validation
            $('input.floatNumber').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g,'').replace(/(\..*)\./g, '$1');
            });

            //
            $('input:radio[name="pay_method_student"]').change(
                function(){

                    var checked = false

                    let selected = '';

                    for (let i = 0;  i < 11 ; i++) {

                        if($('#pay_student'+i).is(':checked')) {
                            checked = true;
                            selected = i;

                            if(selected === 0){
                                app.showTransactionPhoneInputStudent=true
                            }else {
                                app.showTransactionPhoneInputStudent=false
                            }
                            app.new_student.pay_method=i
                            break;
                        }
                    }
                });

            $('input:radio[name="pay_method_academic"]').change(
                function(){

                    var checked = false

                    let selected = '';

                    for (let i = 0;  i < 11 ; i++) {

                        if($('#pay_academic'+i).is(':checked')) {
                            checked = true;
                            selected = i;

                            if(selected === 0){
                                    app.showTransactionPhoneInputAcademic=true

                            }else {
                                app.showTransactionPhoneInputAcademic=false
                            }
                            app.new_academic.pay_method=i;
                            break;
                        }
                    }
                });

            $('input:radio[name="pay_method_enterprise"]').change(
                function(){

                    var checked = false

                    let selected = '';

                    for (let i = 0;  i < 11 ; i++) {

                        if($('#pay_enterprise'+i).is(':checked')) {
                            checked = true;
                            selected = i;

                            if(selected === 0){
                                    app.showTransactionPhoneInputEnterprise=true

                            }else {
                                app.showTransactionPhoneInputEnterprise=false
                            }
                            app.new_enterprise.pay_method=i;
                            break;
                        }
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
                    this.new_academic.country_selected=this.selected_country


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
                    this.new_student.country_selected=this.selected_country

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
                    this.new_enterprise.country_selected=this.selected_country


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