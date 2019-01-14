def outSh() {
  OUT = sh(script: 'ls -alh', returnStdout: true).trim()
  slackSend color: "good", message: "Build: ${env.JOB_NAME} with buildnumber ${env.BUILD_NUMBER} was successful. ${OUT}"
}

pipeline {
  agent any
  stages {
  	stage('Build') {
  		steps {
  			slackSend color: "good", message: "Build start: ${env.GIT_BRANCH} started"
		  	echo 'Hello'
		  	sh 'cd /home/deployer/'
		  	sh "git clone git@github.com:cube13/ddruk_com_ua.git ${env.JOB_NAME}"
		  	slackSend color: "good", message: "Build: ${env.JOB_NAME} with buildnumber ${env.BUILD_NUMBER} was successful"
			}
		}
	stage('Deploy') {
		steps {
  			slackSend color: "good", message: "Deploy start: ${env.JOB_NAME} started"
			sh "rsync -rvae \"ssh -p2212 -i /home/deployer/.ssh/id_rsa\" /home/deployer/${env.JOB_NAME}/ deployer@138.68.59.63:/home/deployer/${env.JOB_NAME}"
  			slackSend color: "good", message: "Deploy: ${env.JOB_NAME} success"
			}
		}
	}
}
