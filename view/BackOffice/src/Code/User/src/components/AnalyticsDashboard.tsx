import { motion } from 'motion/react';
import { 
  TrendingUp, 
  Users, 
  Calendar, 
  HandshakeIcon,
  DollarSign,
  Award,
  BarChart3,
  PieChart,
  Activity
} from 'lucide-react';
import { Card } from './ui/card';
import { SectionTitle } from './SectionTitle';
import {
  LineChart,
  Line,
  BarChart,
  Bar,
  PieChart as RechartsPieChart,
  Pie,
  Cell,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
} from 'recharts';

// Sample data for charts
const monthlyData = [
  { month: 'Jan', participants: 45, events: 8, revenue: 12000 },
  { month: 'Feb', participants: 52, events: 10, revenue: 15000 },
  { month: 'Mar', participants: 38, events: 6, revenue: 9500 },
  { month: 'Apr', participants: 65, events: 12, revenue: 18500 },
  { month: 'May', participants: 78, events: 15, revenue: 22000 },
  { month: 'Jun', participants: 85, events: 14, revenue: 25000 },
];

const eventCategoryData = [
  { name: 'Conferences', value: 142, color: '#10B981' },
  { name: 'Workshops', value: 98, color: '#F59E0B' },
  { name: 'Seminars', value: 85, color: '#6366F1' },
  { name: 'Networking', value: 73, color: '#EC4899' },
];

const sponsorshipData = [
  { category: 'Financial', amount: 85 },
  { category: 'Media', amount: 65 },
  { category: 'Equipment', amount: 45 },
  { category: 'Other', amount: 30 },
];

const participantData = [
  { name: 'Approved', value: 178080, color: '#10B981' },
  { name: 'Pending', value: 98080, color: '#F59E0B' },
  { name: 'Rejected', value: 22000, color: '#EF4444' },
];

export function AnalyticsDashboard() {
  const totalRevenue = 178080;
  const eventSuccess = 75; // percentage

  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      className="space-y-6"
    >
      <SectionTitle title="Analytics & Insights" icon={BarChart3} />

      {/* Top Stats Cards */}
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.1 }}
        >
          <Card className="p-4 bg-gradient-to-br from-green-100 to-green-50 border-0 shadow-md">
            <div className="flex items-center justify-between mb-2">
              <div className="w-10 h-10 rounded-xl bg-green-500 flex items-center justify-center">
                <Calendar className="h-5 w-5 text-white" />
              </div>
            </div>
            <p className="text-xs text-green-700 mb-1">Total Events</p>
            <p className="text-2xl text-green-900">142</p>
            <p className="text-xs text-green-600 mt-1">↑ 12.5% this month</p>
          </Card>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
        >
          <Card className="p-4 bg-gradient-to-br from-amber-100 to-amber-50 border-0 shadow-md">
            <div className="flex items-center justify-between mb-2">
              <div className="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center">
                <Users className="h-5 w-5 text-white" />
              </div>
            </div>
            <p className="text-xs text-amber-700 mb-1">Participants</p>
            <p className="text-2xl text-amber-900">2,450</p>
            <p className="text-xs text-amber-600 mt-1">↑ 8.2% growth</p>
          </Card>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.3 }}
        >
          <Card className="p-4 bg-gradient-to-br from-blue-100 to-blue-50 border-0 shadow-md">
            <div className="flex items-center justify-between mb-2">
              <div className="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center">
                <HandshakeIcon className="h-5 w-5 text-white" />
              </div>
            </div>
            <p className="text-xs text-blue-700 mb-1">Sponsors</p>
            <p className="text-2xl text-blue-900">68</p>
            <p className="text-xs text-blue-600 mt-1">↑ 15.3% increase</p>
          </Card>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.4 }}
        >
          <Card className="p-4 bg-gradient-to-br from-purple-100 to-purple-50 border-0 shadow-md">
            <div className="flex items-center justify-between mb-2">
              <div className="w-10 h-10 rounded-xl bg-purple-500 flex items-center justify-center">
                <Activity className="h-5 w-5 text-white" />
              </div>
            </div>
            <p className="text-xs text-purple-700 mb-1">Active Events</p>
            <p className="text-2xl text-purple-900">24</p>
            <p className="text-xs text-purple-600 mt-1">In progress</p>
          </Card>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.5 }}
        >
          <Card className="p-4 bg-gradient-to-br from-pink-100 to-pink-50 border-0 shadow-md">
            <div className="flex items-center justify-between mb-2">
              <div className="w-10 h-10 rounded-xl bg-pink-500 flex items-center justify-center">
                <Award className="h-5 w-5 text-white" />
              </div>
            </div>
            <p className="text-xs text-pink-700 mb-1">Success Rate</p>
            <p className="text-2xl text-pink-900">92%</p>
            <p className="text-xs text-pink-600 mt-1">Event completion</p>
          </Card>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.6 }}
        >
          <Card className="p-4 bg-gradient-to-br from-indigo-100 to-indigo-50 border-0 shadow-md">
            <div className="flex items-center justify-between mb-2">
              <div className="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center">
                <DollarSign className="h-5 w-5 text-white" />
              </div>
            </div>
            <p className="text-xs text-indigo-700 mb-1">Revenue</p>
            <p className="text-2xl text-indigo-900">$85K</p>
            <p className="text-xs text-indigo-600 mt-1">↑ 22% growth</p>
          </Card>
        </motion.div>
      </div>

      {/* Main Charts Grid */}
      <div className="grid lg:grid-cols-3 gap-6">
        {/* Revenue Analytics - Line Chart */}
        <motion.div
          className="lg:col-span-2"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.7 }}
        >
          <Card className="p-6 bg-white border-2 border-gray-100 shadow-lg">
            <div className="flex items-center justify-between mb-4">
              <div>
                <h3 className="text-[#243B53] mb-1">Event Analytics</h3>
                <div className="flex items-center gap-4 text-sm">
                  <span className="text-gray-500">6 Months</span>
                  <span className="text-2xl text-[#243B53]">{totalRevenue.toLocaleString()}</span>
                </div>
              </div>
              <div className="px-3 py-1 bg-[#243B53] text-white text-xs rounded-lg">
                +15.3%
              </div>
            </div>
            <ResponsiveContainer width="100%" height={250}>
              <LineChart data={monthlyData}>
                <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                <XAxis dataKey="month" stroke="#6B7280" fontSize={12} />
                <YAxis stroke="#6B7280" fontSize={12} />
                <Tooltip 
                  contentStyle={{ 
                    backgroundColor: 'white', 
                    border: '1px solid #E5E7EB',
                    borderRadius: '8px'
                  }}
                />
                <Line 
                  type="monotone" 
                  dataKey="participants" 
                  stroke="#6366F1" 
                  strokeWidth={3}
                  dot={{ fill: '#6366F1', r: 4 }}
                />
              </LineChart>
            </ResponsiveContainer>
          </Card>
        </motion.div>

        {/* Event Success Rate - Circular Progress */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.8 }}
        >
          <Card className="p-6 bg-white border-2 border-gray-100 shadow-lg">
            <h3 className="text-[#243B53] mb-4">Event Success Rate</h3>
            <div className="flex items-center justify-center relative h-48">
              <svg className="w-40 h-40 transform -rotate-90">
                <circle
                  cx="80"
                  cy="80"
                  r="70"
                  stroke="#F3F4F6"
                  strokeWidth="12"
                  fill="none"
                />
                <circle
                  cx="80"
                  cy="80"
                  r="70"
                  stroke="url(#gradient)"
                  strokeWidth="12"
                  fill="none"
                  strokeDasharray={`${(eventSuccess / 100) * 440} 440`}
                  strokeLinecap="round"
                />
                <defs>
                  <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stopColor="#6366F1" />
                    <stop offset="100%" stopColor="#8B5CF6" />
                  </linearGradient>
                </defs>
              </svg>
              <div className="absolute inset-0 flex flex-col items-center justify-center">
                <span className="text-4xl text-[#243B53]">{eventSuccess}%</span>
                <span className="text-sm text-gray-500">Success Rate</span>
              </div>
            </div>
            <div className="mt-4 p-3 bg-green-50 rounded-lg text-center">
              <p className="text-2xl text-green-900">$18,158.21</p>
              <p className="text-xs text-green-600 mt-1">↑ 8% from last month</p>
            </div>
          </Card>
        </motion.div>
      </div>

      {/* Bottom Charts Grid */}
      <div className="grid lg:grid-cols-3 gap-6">
        {/* Event Categories - Donut Chart */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.9 }}
        >
          <Card className="p-6 bg-white border-2 border-gray-100 shadow-lg">
            <h3 className="text-[#243B53] mb-4">Event Distribution</h3>
            <ResponsiveContainer width="100%" height={220}>
              <RechartsPieChart>
                <Pie
                  data={eventCategoryData}
                  cx="50%"
                  cy="50%"
                  innerRadius={60}
                  outerRadius={80}
                  paddingAngle={5}
                  dataKey="value"
                >
                  {eventCategoryData.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={entry.color} />
                  ))}
                </Pie>
                <Tooltip />
              </RechartsPieChart>
            </ResponsiveContainer>
            <div className="grid grid-cols-2 gap-2 mt-4">
              {eventCategoryData.map((item, index) => (
                <div key={index} className="flex items-center gap-2">
                  <div 
                    className="w-3 h-3 rounded-full" 
                    style={{ backgroundColor: item.color }}
                  />
                  <span className="text-xs text-gray-600">{item.name}</span>
                </div>
              ))}
            </div>
          </Card>
        </motion.div>

        {/* Sponsorship by Type - Bar Chart */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 1.0 }}
        >
          <Card className="p-6 bg-white border-2 border-gray-100 shadow-lg">
            <h3 className="text-[#243B53] mb-4">Sponsorship Types</h3>
            <div className="space-y-4">
              {sponsorshipData.map((item, index) => (
                <div key={index}>
                  <div className="flex items-center justify-between mb-1">
                    <span className="text-sm text-gray-600">{item.category}</span>
                    <span className="text-sm text-[#243B53]">{item.amount}%</span>
                  </div>
                  <div className="w-full bg-gray-100 rounded-full h-2">
                    <motion.div
                      className="h-2 rounded-full"
                      style={{ 
                        backgroundColor: ['#10B981', '#F59E0B', '#6366F1', '#EC4899'][index],
                        width: `${item.amount}%`
                      }}
                      initial={{ width: 0 }}
                      animate={{ width: `${item.amount}%` }}
                      transition={{ delay: 1.1 + index * 0.1, duration: 0.8 }}
                    />
                  </div>
                </div>
              ))}
            </div>
          </Card>
        </motion.div>

        {/* Participant Status */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 1.1 }}
        >
          <Card className="p-6 bg-white border-2 border-gray-100 shadow-lg">
            <h3 className="text-[#243B53] mb-4">Participant Status</h3>
            <div className="space-y-4">
              {participantData.map((item, index) => (
                <div 
                  key={index}
                  className="p-4 rounded-xl border-2 border-gray-100 hover:border-gray-200 transition-colors"
                >
                  <div className="flex items-center justify-between mb-2">
                    <div className="flex items-center gap-2">
                      <div 
                        className="w-10 h-10 rounded-full flex items-center justify-center"
                        style={{ backgroundColor: item.color + '20' }}
                      >
                        <Users className="h-5 w-5" style={{ color: item.color }} />
                      </div>
                      <span className="text-sm text-gray-600">{item.name}</span>
                    </div>
                  </div>
                  <p className="text-xl text-[#243B53]">{item.value.toLocaleString()}</p>
                  <p className="text-xs text-gray-500 mt-1">
                    {index === 0 ? '↑ 12.5%' : index === 1 ? '→ Stable' : '↓ 3.2%'} from last month
                  </p>
                </div>
              ))}
            </div>
          </Card>
        </motion.div>
      </div>

      {/* Monthly Comparison - Bar Chart */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 1.2 }}
      >
        <Card className="p-6 bg-white border-2 border-gray-100 shadow-lg">
          <h3 className="text-[#243B53] mb-4">Monthly Performance</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={monthlyData}>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="month" stroke="#6B7280" fontSize={12} />
              <YAxis stroke="#6B7280" fontSize={12} />
              <Tooltip 
                contentStyle={{ 
                  backgroundColor: 'white', 
                  border: '1px solid #E5E7EB',
                  borderRadius: '8px'
                }}
              />
              <Legend />
              <Bar dataKey="participants" fill="#6366F1" radius={[8, 8, 0, 0]} />
              <Bar dataKey="events" fill="#10B981" radius={[8, 8, 0, 0]} />
              <Bar dataKey="revenue" fill="#F59E0B" radius={[8, 8, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </Card>
      </motion.div>
    </motion.div>
  );
}
