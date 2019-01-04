export CLASSPATH=".:$1/bcmail-jdk16-1.44.jar:$1/bcprov-jdk16-1.44.jar"
cd $1; cd deliverable; java pkcs7gen oupt.jks $2 $3 $4;
cd $1; javac APIBased.java;
cd $1; java APIBased $3 $5;