// utils -> htmlToPdf.js
// Export page to PDF format
import html2Canvas from "html2canvas";
import JsPDF from "jspdf";
export default {
  install(Vue, options) {
    Vue.prototype.getPdf = function() {
      var title = this.htmlTitle;     // Need to export the title of the page, declare htmlTitle in the data of the exported page, see the code below for details
      html2Canvas(document.querySelector("#pdfDom"), {    // The id of the container that needs to export the content of the page is the same as "#pdfDom" here
        allowTaint: true,
        useCORS: true
      }).then(function(canvas) {
        let contentWidth = canvas.width;
        let contentHeight = canvas.height;
        let pageHeight = (contentWidth / 592.28) * 841.89;
        //let pageHeight = 408;
        let leftHeight = contentHeight;
        let position = 0;
        let imgWidth = 595.28;
        let imgHeight = (592.28 / contentWidth) * contentHeight;
        let pageData = canvas.toDataURL("image/jpeg", 1.0);
        let PDF = new JsPDF("", "pt", "a4");
        if (leftHeight < pageHeight) {
          PDF.addImage(pageData, "JPEG", 0, 0, imgWidth, imgHeight);
        } else {
          while (leftHeight > 0) {
            PDF.addImage(pageData, "JPEG", 0, position, imgWidth, imgHeight);
            leftHeight -= pageHeight;
            position -= 841.89;
            if (leftHeight > 0) {
              PDF.addPage();
            }
          }
        }
        PDF.save(title + ".pdf");
      });
    };
  }
};
