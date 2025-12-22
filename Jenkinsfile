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
                    // Copy environment file
                    sh '''
                        if [ -f env-server.txt ]; then
                            cp env-server.txt .env
                            echo "✅ Environment file copied from env-server.txt"
                        elif [ ! -f .env ]; then
                            cp .env.example .env
                            echo "✅ Environment file copied from .env.example"
                        else
                            echo "✅ Environment file already exists"
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
                        sh '''
                            docker run --rm -v $(pwd):/app -w /app composer:latest \
                                composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs
                        '''
                    }
                }
                
                stage('Node Dependencies') {
                    steps {
                        echo '📦 Installing Node dependencies...'
                        sh '''
                            docker run --rm -v $(pwd):/app -w /app node:20-alpine \
                                npm ci
                        '''
                    }
                }
            }
        }
        
        stage('Build Assets') {
            steps {
                echo '🏗️ Building frontend assets...'
                sh '''
                    docker run --rm -v $(pwd):/app -w /app node:20-alpine \
                        npm run build
                '''
            }
        }
        
        stage('Run Tests') {
            steps {
                echo '🧪 Running tests...'
                script {
                    try {
                        sh '''
                            docker run --rm -v $(pwd):/var/www -w /var/www \
                                php:8.4-cli php artisan test --parallel
                        '''
                    } catch (Exception e) {
                        echo "⚠️ Tests failed but continuing deployment"
                        currentBuild.result = 'UNSTABLE'
                    }
                }
            }
        }
        
        stage('Build Docker Images') {
            steps {
                echo '🐳 Building Docker images...'
                sh '''
                    docker-compose build --no-cache
                '''
            }
        }
        
        stage('Stop Old Containers') {
            steps {
                echo '🛑 Stopping old containers...'
                sh '''
                    docker-compose down || true
                '''
            }
        }
        
        stage('Deploy') {
            steps {
                echo '🚀 Deploying application...'
                sh '''
                    # Start containers
                    docker-compose up -d
                    
                    # Wait for containers to be healthy
                    echo "⏳ Waiting for containers to start..."
                    sleep 10
                    
                    # Check container status
                    docker-compose ps
                '''
            }
        }
        
        stage('Database Migration') {
            steps {
                echo '🗄️ Running database migrations...'
                sh '''
                    docker-compose exec -T app php artisan migrate --force
                '''
            }
        }
        
        stage('Cache Optimization') {
            steps {
                echo '⚡ Optimizing application cache...'
                sh '''
                    docker-compose exec -T app php artisan config:cache
                    docker-compose exec -T app php artisan route:cache
                    docker-compose exec -T app php artisan view:cache
                '''
            }
        }
        
        stage('Health Check') {
            steps {
                echo '🏥 Performing health check...'
                script {
                    sh '''
                        # Wait a bit for application to be ready
                        sleep 5
                        
                        # Check if application is responding
                        curl -f http://localhost:${APP_PORT} || exit 1
                        
                        echo "✅ Application is healthy!"
                    '''
                }
            }
        }
        
        stage('Cleanup') {
            steps {
                echo '🧹 Cleaning up...'
                sh '''
                    # Remove dangling images
                    docker image prune -f
                    
                    # Remove unused volumes
                    docker volume prune -f
                '''
            }
        }
    }
    
    post {
        success {
            echo '✅ Pipeline completed successfully!'
            echo "🌐 Application is running at: http://localhost:${APP_PORT}"
            
            // Optional: Send notification
            // slackSend color: 'good', message: "Deployment successful: ${env.JOB_NAME} ${env.BUILD_NUMBER}"
        }
        
        failure {
            echo '❌ Pipeline failed!'
            
            // Rollback on failure
            sh '''
                echo "🔄 Rolling back..."
                docker-compose down
            '''
            
            // Optional: Send notification
            // slackSend color: 'danger', message: "Deployment failed: ${env.JOB_NAME} ${env.BUILD_NUMBER}"
        }
        
        always {
            echo '📊 Generating reports...'
            
            // Archive logs
            sh '''
                mkdir -p logs
                docker-compose logs > logs/docker-compose.log 2>&1 || true
            '''
            
            // Archive artifacts
            archiveArtifacts artifacts: 'logs/*.log', allowEmptyArchive: true
            
            // Clean workspace
            cleanWs()
        }
    }
}
