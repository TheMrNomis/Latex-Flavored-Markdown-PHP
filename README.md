Latex-Flavored-Markdown-PHP
===========================
simple script to translate markdown into latex-generated PDF

usage
-----
`./LFM.php <filename.md>` (from inside the Latex-Flavored-Markdown-PHP folder)
* `-q`, `--quiet` : quiet mode (no term output)
* `-n`, `--nolatex` : generates only the tex file, does not render the PDF
* `-l`, `--latex` : renders the PDF (default behavior)
* `-t`, `--term-output` : prints the tex file directly in the terminal (doesn't render the PDF)
* `-f`, `--file-output` : writes the tex into a file (default behavior)
* `-o`, `--output` : specifies the filename of the output (default : `./tex/LFMOutput.tex` and `./tex/LFMOutput.pdf`)
* `-i`, `--input` : specifies the markdown file to read (default : last argument not starting with a `-`)
