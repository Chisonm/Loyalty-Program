import React from 'react'
import { ArrowLeft, Warning2, Refresh } from 'iconsax-reactjs'

interface UserStatsErrorProps {
  className?: string
  onRetry?: () => void
  onBackToDashboard?: () => void
  error?: string | null
}

const UserStatsError: React.FC<UserStatsErrorProps> = ({ 
  className = '', 
  onRetry,
  onBackToDashboard,
  error 
}) => {
  return (
    <div className={`mx-auto ${className}`}>
      {/* Back button and header */}
      <div className="mb-6 sm:mb-8">
        <button
          onClick={onBackToDashboard}
          className="flex items-center gap-2 text-primary-green hover:text-primary-green/80 mb-4 transition-colors"
        >
          <ArrowLeft size="20" />
          <span className="text-sm font-medium">Back to Users</span>
        </button>
        
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
          Badge & Achievement Overview
        </h1>
        <p className="text-gray-600">Failed to load user achievements</p>
      </div>

      {/* Error content */}
      <div className="bg-white border custom-border-color rounded-lg overflow-hidden">
        <div className="p-8 text-center">
          <div className="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <Warning2 className="h-8 w-8 text-red-600" variant="Bold" />
          </div>
          
          <h3 className="text-lg font-medium text-gray-900 mb-2">
            Failed to Load User Data
          </h3>
          
          <p className="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
            {error || "We couldn't fetch the user achievements data. This might be due to a network issue or server problem."}
          </p>
          
          <div className="flex flex-col sm:flex-row gap-3 justify-center items-center">
            {onRetry && (
              <button
                onClick={onRetry}
                className="inline-flex items-center gap-2 px-4 py-2 bg-primary-green text-white rounded-lg hover:bg-primary-green/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-green focus:ring-offset-2"
              >
                <Refresh className="h-4 w-4" />
                Try Again
              </button>
            )}
            
            <button
              onClick={() => window.location.reload()}
              className="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
              Refresh Page
            </button>
          </div>
          
          <div className="mt-6 pt-6 border-t border-gray-200">
            <details className="text-left">
              <summary className="cursor-pointer text-sm text-gray-400 hover:text-gray-600 mb-2">
                Technical Details
              </summary>
              <div className="text-xs text-gray-500 bg-gray-50 p-3 rounded font-mono">
                {error ? (
                  <div>
                    <strong>Error:</strong> {error}
                  </div>
                ) : (
                  <div>
                    <strong>Status:</strong> Network or Server Error<br />
                    <strong>Time:</strong> {new Date().toLocaleString()}
                  </div>
                )}
              </div>
            </details>
          </div>
        </div>
      </div>
    </div>
  )
}

export default UserStatsError