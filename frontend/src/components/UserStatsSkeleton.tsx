import React from 'react'
import { ArrowLeft } from 'iconsax-reactjs'

interface UserStatsSkeletonProps {
  className?: string
}

const UserStatsSkeleton: React.FC<UserStatsSkeletonProps> = ({ 
  className = '' 
}) => {
  return (
    <div className={`mx-auto ${className}`}>
      {/* Back button and header skeleton */}
      <div className="mb-6 sm:mb-8">
        <div className="flex items-center gap-2 text-gray-400 mb-4">
          <ArrowLeft size="20" />
          <span className="text-sm font-medium">Back to Users</span>
        </div>
        
        <div className="animate-pulse">
          <div className="h-8 bg-gray-200 rounded w-1/3 mb-2"></div>
          <div className="h-4 bg-gray-200 rounded w-1/2"></div>
        </div>
      </div>
      
      {/* Main content skeleton */}
      <div className="grid sm:grid-cols-3 gap-6 sm:gap-8 md:gap-10 lg:gap-12 xl:gap-14 items-start">
        <div className="col-span-2">
          {/* Progress card skeleton */}
          <div className="bg-gray-200 py-6 px-4 rounded-lg relative animate-pulse">
            <div className="flex justify-between items-center">
              <div className="h-8 bg-gray-300 rounded w-32"></div>
              <div className="h-6 bg-gray-300 rounded w-24"></div>
            </div>
            {/* Progress bar skeleton */}
            <div className="mt-4 h-3 bg-gray-300 rounded-full"></div>
          </div>

          {/* Achievements section skeleton */}
          <div className="bg-white border custom-border-color py-6 px-4 w-full rounded-lg relative mt-10">
            <div className="animate-pulse">
              <div className="h-8 bg-gray-200 rounded w-32 mb-6"></div>
              <div className="flex flex-col gap-6">
                {Array.from({ length: 3 }).map((_, i) => (
                  <div key={i} className="flex items-center gap-4">
                    <div className="w-12 h-12 bg-gray-200 rounded-full"></div>
                    <div className="flex-1">
                      <div className="h-5 bg-gray-200 rounded w-3/4 mb-2"></div>
                      <div className="h-4 bg-gray-200 rounded w-full mb-3"></div>
                      <div className="h-2 bg-gray-200 rounded-full"></div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Unlocked Achievements section skeleton */}
          <div className="bg-white border custom-border-color py-6 px-4 w-full rounded-lg relative mt-10">
            <div className="animate-pulse">
              <div className="h-8 bg-gray-200 rounded w-48 mb-6"></div>
              <div className="flex flex-col gap-6">
                {Array.from({ length: 2 }).map((_, i) => (
                  <div key={`unlocked-${i}`} className="flex items-center gap-4">
                    <div className="w-12 h-12 bg-green-200 rounded-full"></div>
                    <div className="flex-1">
                      <div className="h-5 bg-gray-200 rounded w-3/4 mb-2"></div>
                      <div className="h-4 bg-gray-200 rounded w-full mb-3"></div>
                      <div className="h-2 bg-green-200 rounded-full"></div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
        
        {/* Badges section skeleton */}
        <div className="col-span-1">
          <div className="bg-white border custom-border-color py-6 px-4 w-full rounded-lg relative">
            <div className="animate-pulse">
              <div className="h-8 bg-gray-200 rounded w-20 mb-6"></div>
              <div className="grid sm:grid-cols-3 gap-6 sm:gap-3 md:gap-4 lg:gap-8 xl:gap-10 w-full">
                {Array.from({ length: 4 }).map((_, i) => (
                  <div key={i} className="flex flex-col items-center">
                    <div className="w-12 h-12 bg-gray-200 rounded-full mb-2"></div>
                    <div className="h-4 bg-gray-200 rounded w-12"></div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

export default UserStatsSkeleton