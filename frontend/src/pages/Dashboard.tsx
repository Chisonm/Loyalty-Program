import React, { useCallback, useMemo } from 'react'
import { useNavigate } from 'react-router-dom'
import UsersTable from "../components/UsersTable"
import { useUsers } from "../hooks/useUsers"

const Dashboard: React.FC = () => {
  const navigate = useNavigate()
  const { data: usersData, isLoading, isError, error, refetch } = useUsers()

  // Memoize users array to prevent unnecessary re-renders
  const users = useMemo(() => usersData?.users || [], [usersData?.users])

  // Memoize event handlers to prevent unnecessary re-renders
  const handleViewUser = useCallback((userId: string) => {
    navigate(`/user/${userId}/achievements`)
  }, [navigate])

  const handleRetry = useCallback(() => {
    refetch()
  }, [refetch])

  return (
    <div className="mx-auto">
      <div className="mb-6 sm:mb-8">
        <h1 className="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
          Users Management
        </h1>
        <p className="text-gray-600">Manage and view all users in the system</p>
      </div>

      <UsersTable 
        users={users}
        isLoading={isLoading}
        isError={isError}
        error={error?.message || null}
        onViewUser={handleViewUser}
        onRetry={handleRetry}
      />
    </div>
  )
}

export default Dashboard