const btnGenerate = document.querySelector("#generate_pdf");
btnGenerate.addEventListener("click", () => {

    //Conteúdo do PDF 
    const content = document.querySelector("#content")

    //Configuração do arquivo final de Pdf
    const options = {
        filename: "atrasados.pdf",
        html2canvas: {scale: 2},
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait"},
    };

    //Gerar e Baixar PDF
    html2pdf().set(options).from(content).save();
})