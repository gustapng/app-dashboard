$(document).ready(() => {
	
    $('#documentacao').on('click', () => {
        // $('#pagina').load('documentacao.html')

        // $.get('documentacao.html', data => {
        //     $('#pagina').html(data)
        // })
        
        $.post('documentacao.html', data => {
            $('#pagina').html(data);
        })

    })

    $('#suporte').on('click', () => {
        //$('#pagina').load('suporte.html')

        // $.get('suporte.html', data => {
        //     $('#pagina').html(data)
        // })
        
        $.post('suporte.html', data => {
            $('#pagina').html(data);
        })
    })

    $('#feedback').on('click', () => {
        //$('#pagina').load('suporte.html')

        // $.get('suporte.html', data => {
        //     $('#pagina').html(data)
        // })
        
        $.post('feedback.html', data => {
            $('#pagina').html(data);
        })
    })

    //ajax
    $('#competencia').on('change', e => {

        let competencia = $(e.target).val();

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`, //x-www-form-urlencoded
            dataType: 'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#totalAtivos').html(dados.usuariosAtivos)
                $('#totalInativos').html(dados.usuariosInativos)
                $('#totalDespesas').html(dados.totalDespesas)
                $('#totalReclamacoes').html(dados.totalReclamacoes)
                $('#totalElogios').html(dados.totalElogios)
                $('#totalSugestoes').html(dados.totalSugestoes)
                //console.log(dados)
            },
            error: erro => console.log(erro)
        })

        //métodos, url, dados, sucesso, erro
    })
})