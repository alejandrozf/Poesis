(in-package :cl-user)

(defpackage :nlpes-asd
  (:use :cl :asdf))

(in-package :nlpes-asd)

(defsystem :nlpes
  :description "Some Natural Language Processing tools in Spanish(ES) language.
               Warning: Comments,documentation,etc are in Spanish!"
  :version "0.0.1"
  :serial t
  :author "Alejandro Zamora Fonseca <ale2014.zamora@gmail.com>"
  :licence "MIT License"
  :components ((:file "package") 
	       (:file "modelo")
	       (:file "utiles")
	       (:file "vocales" :depends-on ("modelo"))
	       (:file "fonemas" :depends-on ("utiles" "vocales"))
	       (:file "silabas" :depends-on ("fonemas"))
	       (:file "acentuacion" :depends-on ("silabas"))
	       (:file "metrica" :depends-on ("acentuacion"))
	       (:file "rima" :depends-on ("acentuacion"))))

