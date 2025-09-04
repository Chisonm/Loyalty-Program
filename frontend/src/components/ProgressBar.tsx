import React from 'react'

interface ProgressBarProps {
  value: number
  max?: number
  className?: string
  barClassName?: string
  backgroundColor?: string
  color?: string
  height?: string
  showPercentage?: boolean
  animated?: boolean
}

const ProgressBar: React.FC<ProgressBarProps> = ({
  value,
  max = 100,
  className = '',
  barClassName = '',
  backgroundColor = 'bg-gray-200',
  color = 'bg-white',
  height = 'h-2',
  showPercentage = false,
  animated = true
}) => {
  const percentage = Math.min(Math.max((value / max) * 100, 0), 100)

  return (
    <div className={`w-full ${className}`}>
      <div className={`${backgroundColor} ${height} rounded-full overflow-hidden ${className}`}>
        <div
          className={`${height} ${color} rounded-full ${animated ? 'transition-all duration-500 ease-out' : ''} ${barClassName}`}
          style={{ width: `${percentage}%` }}
        />
      </div>
      {showPercentage && (
        <div className="mt-1 text-sm text-gray-600">
          {Math.round(percentage)}%
        </div>
      )}
    </div>
  )
}

export default ProgressBar