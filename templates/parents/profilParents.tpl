<div class="row">

    <div class="col-md-4 col-sm-12">

        {include file="parents/formulaireParents.tpl"}

    </div>
    <!-- col-md-... -->


    <div class="col-md-8 col-sm-12">

        {include file="parents/profileHelp.tpl"}

    </div>
    <!-- div-md-... -->

</div>
<!-- row -->


<script type="text/javascript">
    function countLettres(chaine) {
        return (chaine.match(/[a-zA-Z]/g) == null) ? 0 : chaine.match(/[a-zA-Z]/g).length;
    }

    function countChiffres(chaine) {
        return (chaine.match(/[0-9]/g) == null) ? 0 : chaine.match(/[0-9]/g).length;
    }

    jQuery.validator.addMethod('goodPwd', function(value, element) {
        // validation longueur
        var condLength = (value.length >= 9);
        // validation 2 chiffres min
        var condChiffres = (countChiffres(value) >= 2)
            // validation 2 lettres min
        var condLettres = (countLettres(value) >= 2)

        var testOK = (condLength && condChiffres && condLettres);
        return this.optional(element) || testOK;
    }, "Complexité insuffisante");


    $(document).ready(function() {

        $('#saveProfile').click(function(){
            if ($('#formProfilParent').valid()) {
                var formulaire = $('#formProfilParent').serialize();
                $.post('inc/parents/saveProflParent.inc.php', {
                    formulaire: formulaire
                }, function(resultat){

                })

            }

        })




        $(".help").hide();
        $(".fa-help").css('cursor', 'pointer');

        $(".inputHelp").focus(function() {
            var id = $(this).attr('id');
            $(".help").hide();
            $("#texte_" + id).fadeIn();
        })

        $(".inputHelp").blur(function() {
            $(".help").hide();
        })

        $(".fa-help").hover(function() {
            var id = $(this).closest('.input-group').find('.inputHelp').attr('id');
            $(".help").hide();
            $("#texte_" + id).fadeIn();
        })

        $("#choixLien li a").click(function() {
            $("#lien").val($(this).data('value'));
            $("#lien").select();
        })

        $("#formProfilParent").validate({
            rules: {
                formule: {
                    required: true
                },
                nom: {
                    required: true
                },
                prenom: {
                    required: true
                },
                userName: {
                    required: true
                },
                mail: {
                    required: true,
                    email: true
                },
                lien: {
                    required: true
                },
                passwd: {
                    goodPwd: true
                },
                passwd2: {
                    equalTo: "#passwd"
                }
            },
            messages: {
                lien: {
                    maxlength: 'Veuillez préciser s.v.p.'
                }
            }
        });

    })
</script>
