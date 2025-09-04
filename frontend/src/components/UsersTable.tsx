import React, { memo } from 'react'
import { Eye } from 'iconsax-reactjs'
import UsersTableSkeleton from './UsersTableSkeleton'
import UsersTableError from './UsersTableError'
import type { ApiUser as User } from '../types'

interface UserRowProps {
  user: User
  onViewUser?: (userId: string) => void
}

const UserRow = memo<UserRowProps>(({ user, onViewUser }) => {
  const status = user.email_verified_at 
    ? { label: 'Verified', color: 'bg-green-100 text-green-800' }
    : { label: 'Unverified', color: 'bg-yellow-100 text-yellow-800' }
  
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString()
  }

  return (
    <tr key={user.id} className="hover:bg-gray-50">
      <td className="px-6 py-4 whitespace-nowrap">
        <div className="flex items-center">
          <div className="w-10 h-10 bg-primary-green-light rounded-full flex items-center justify-center">
            <span className="text-sm font-medium text-primary-green">
              {user.name.charAt(0).toUpperCase()}
            </span>
          </div>
          <div className="ml-4">
            <div className="text-sm font-medium text-gray-900">{user.name}</div>
            <div className="text-sm text-gray-500 truncate max-w-xs">
              ID: {user.id.split('-')[0]}...
            </div>
          </div>
        </div>
      </td>
      <td className="px-6 py-4 whitespace-nowrap">
        <div className="text-sm text-gray-900">{user.email}</div>
      </td>
      <td className="px-6 py-4 whitespace-nowrap">
        <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${status.color}`}>
          {status.label}
        </span>
      </td>
      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {formatDate(user.created_at)}
      </td>
      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <button
          onClick={() => onViewUser?.(user.id)}
          className="text-blue-600 hover:text-blue-900 p-1 inline-flex items-center gap-1"
          title="View achievements"
        >
          <Eye size="16" />
          <span className="text-xs">View</span>
        </button>
      </td>
    </tr>
  )
})

interface UsersTableProps {
  users: User[]
  isLoading?: boolean
  isError?: boolean
  error?: string | null
  onViewUser?: (userId: string) => void
  onRetry?: () => void
  className?: string
}

const UsersTable: React.FC<UsersTableProps> = ({
  users,
  isLoading = false,
  isError = false,
  error = null,
  onViewUser,
  onRetry,
  className = ''
}) => {

  if (isError) {
    return (
      <UsersTableError 
        className={className} 
        onRetry={onRetry}
        error={error}
      />
    )
  }

  if (isLoading) {
    return <UsersTableSkeleton className={className} />
  }

  return (
    <div className={`bg-white border custom-border-color rounded-lg overflow-hidden ${className}`}>
      <div className="px-6 py-4 border-b custom-border-color">
        <h2 className="text-xl font-semibold text-gray-900">Users</h2>
      </div>
      
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                User
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Joined
              </th>
              <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Action
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {users.map((user) => (
              <UserRow 
                key={user.id} 
                user={user} 
                onViewUser={onViewUser} 
              />
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default memo(UsersTable)