pipeline {
    agent any
    
    environment {
        // Docker Configuration
        DOCKER_IMAGE = 'swap-hub-app'
        DOCKER_COMPOSE_FILE = 'docker-compose.yml'
        
        // Application Configuration
        APP_NAME = 'swap-hub'
        APP_PORT = '5541'
        
        // Git Configuration
        GIT_BRANCH = 'main'
    }
    
    stages {
        stage('Checkout') {
            steps {
                echo '📥 Checking out code from repository...'
                checkout scm
            }
        }
        
        stage('Environment Setup') {
            steps {
                echo '⚙️ Setting up environment...'
                script {
                    // Copy environment file if exists, otherwise use example
                    sh '''
                        if [ -f env-server.txt ]; then
                            cp env-server.txt .env
                            echo "✅ Environment file copied from env-server.txt"
                        elif [ ! -f .env ]; then
                            cp .env.example .env
                            echo "⚠️ .env not found, using .env.example"
                        fi
                    '''
                }
            }
        }
        
        stage('Install Dependencies') {
            parallel {
                stage('PHP Dependencies') {
                    steps {
                        echo '📦 Installing PHP dependencies...'
                        sh 'docker run --rm -v $(pwd):/app -w /app composer:latest composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs'
                    }
                }
                
                stage('Node Dependencies') {
                    steps {
                        echo '📦 Installing Node dependencies...'
                        sh 'docker run --rm -v $(pwd):/app -w /app node:20-alpine npm ci'
                    }
                }
            }
        }
        
        stage('Linting') {
            steps {
                echo '🧹 Checking code style (Laravel Pint)...'
                sh 'docker run --rm -v $(pwd):/app -w /app php:8.4-cli php vendor/bin/pint --test'
            }
        }
        
        stage('Build Assets') {
            steps {
                echo '🏗️ Building frontend assets...'
                sh 'docker run --rm -v $(pwd):/app -w /app node:20-alpine npm run build'
            }
        }
        
        stage('Run Tests') {
            steps {
                echo '🧪 Running tests...'
                // No try-catch here: if tests fail, the pipeline STOPS.
                sh 'docker run --rm --entrypoint php -v $(pwd):/var/www -w /var/www swap-hub-app-development:latest artisan test'
            }
        }
        
        stage('Deploy') {
            steps {
                echo '🚀 Deploying application (Zero-Downtime Recreate)...'
                sh '''
                    # Pull and build new images, then restart containers with minimal downtime
                    docker-compose up -d --build --remove-orphans
                '''
            }
        }
        
        stage('Database Migration') {
            steps {
                echo '🗄️ Running database migrations...'
                sh 'docker-compose exec -T app php artisan migrate --force'
            }
        }
        
        stage('Optimize') {
            steps {
                echo '⚡ Optimizing application performance...'
                sh '''
                    docker-compose exec -T app php artisan optimize
                    docker-compose exec -T app php artisan view:cache
                '''
            }
        }
        
        stage('Health Check') {
            steps {
                echo '🏥 Performing health check...'
                script {
                    // Retry mechanism for health check
                    timeout(time: 2, unit: 'MINUTES') {
                        waitUntil {
                            def response = sh(script: "curl -s -o /dev/null -w '%{http_code}' http://localhost:${APP_PORT}", returnStdout: true).trim()
                            return (response == '200')
                        }
                    }
                    echo "✅ Application is healthy!"
                }
            }
        }
        
        stage('Cleanup') {
            steps {
                echo '🧹 Cleaning up old Docker resources...'
                sh 'docker image prune -f'
            }
        }
    }
    
    post {
        success {
            echo '✅ Pipeline completed successfully!'
            echo "🌐 Application is running at: http://localhost:${APP_PORT}"
        }
        
        failure {
            echo '❌ Pipeline failed! Deployment aborted or rolled back.'
            // Optional: Archive Laravel logs for debugging
            sh 'docker-compose logs app > laravel_error.log || true'
            archiveArtifacts artifacts: 'laravel_error.log', allowEmptyArchive: true
        }
        
        always {
            cleanWs()
        }
    }
}
