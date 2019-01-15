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
			sh "pwd",
	        echo $WORKSPACE
		  	sh "git pull git@github.com:cube13/ddruk_com_ua.git"
		  	slackSend color: "good", message: "Build: ${env.JOB_NAME} with buildnumber ${env.BUILD_NUMBER} was successful"
			}
		}
	stage('Deploy') {
		steps {
  			slackSend color: "good", message: "Deploy start: ${env.JOB_NAME} started"
			slackSend color: "good", message: "Deploy: ${env.BUILD_TAG} success"
			}
		}
	}
  post {
   	success {
      		slackSend (color: '#00FF00', message: "SUCCESSFUL: Job '${env.JOB_NAME} [${env.NODE_NAME}]' (${env.BUILD_URL})")
    		}

    	failure {
      		slackSend (color: '#FF0000', message: "FAILED: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")

    		}
  	}
}