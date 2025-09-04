import React from 'react'

interface UsersTableSkeletonProps {
  className?: string
  rows?: number
}

const UsersTableSkeleton: React.FC<UsersTableSkeletonProps> = ({ 
  className = '', 
  rows = 5 
}) => {
  return (
    <div className={`bg-white border custom-border-color rounded-lg overflow-hidden ${className}`}>
      <div className="px-6 py-4 border-b custom-border-color">
        <div className="h-6 bg-gray-200 rounded w-20 animate-pulse"></div>
      </div>
      
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left">
                <div className="h-3 bg-gray-200 rounded w-12 animate-pulse"></div>
              </th>
              <th className="px-6 py-3 text-left">
                <div className="h-3 bg-gray-200 rounded w-12 animate-pulse"></div>
              </th>
              <th className="px-6 py-3 text-left">
                <div className="h-3 bg-gray-200 rounded w-12 animate-pulse"></div>
              </th>
              <th className="px-6 py-3 text-left">
                <div className="h-3 bg-gray-200 rounded w-12 animate-pulse"></div>
              </th>
              <th className="px-6 py-3 text-right">
                <div className="h-3 bg-gray-200 rounded w-12 ml-auto animate-pulse"></div>
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {Array.from({ length: rows }).map((_, i) => (
              <tr key={i} className="animate-pulse">
                <td className="px-6 py-4 whitespace-nowrap">
                  <div className="flex items-center">
                    <div className="w-10 h-10 bg-gray-200 rounded-full"></div>
                    <div className="ml-4">
                      <div className="h-4 bg-gray-200 rounded w-24 mb-2"></div>
                      <div className="h-3 bg-gray-200 rounded w-16"></div>
                    </div>
                  </div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <div className="h-4 bg-gray-200 rounded w-32"></div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <div className="h-6 bg-gray-200 rounded-full w-16"></div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <div className="h-4 bg-gray-200 rounded w-20"></div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-right">
                  <div className="h-6 bg-gray-200 rounded w-12 ml-auto"></div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default UsersTableSkeleton