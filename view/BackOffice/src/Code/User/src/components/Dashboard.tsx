import { useState } from 'react';
import { motion } from 'motion/react';
import { 
  Calendar, 
  Users, 
  TrendingUp, 
  Award,
  Plus,
  Mail,
  Eye,
  UserPlus,
  HandshakeIcon,
  CheckCircle,
  Clock,
  XCircle,
  Edit,
  Phone,
  Zap,
  CalendarDays
} from 'lucide-react';
import { Sidebar } from './Sidebar';
import { DashboardHeader } from './DashboardHeader';
import { EventCard } from './EventCard';
import { StatCard } from './StatCard';
import { QuickActionCard } from './QuickActionCard';
import { SectionTitle } from './SectionTitle';
import { EventCalendar } from './EventCalendar';
import { CreateEventForm } from './CreateEventForm';
import { CreateSponsorForm } from './CreateSponsorForm';
import { AnalyticsDashboard } from './AnalyticsDashboard';
import { TaskBoard } from './TaskBoard';
import { Card } from './ui/card';
import { Button } from './ui/button';
import { Badge } from './ui/badge';
import { events, participants, sponsors, analyticsData } from '../data/events';
import { StarIcon } from './StarIcon';

export function Dashboard() {
  const [activeTab, setActiveTab] = useState('dashboard');
  const [showCreateEvent, setShowCreateEvent] = useState(false);
  const [showCreateSponsor, setShowCreateSponsor] = useState(false);

  const upcomingEvents = events.filter(e => e.status === 'upcoming').slice(0, 3);
  const recentParticipants = participants.slice(0, 5);
  const recentSponsors = sponsors.slice(0, 3);
  const pendingParticipants = participants.filter(p => p.status === 'pending');

  const statusColors = {
    'upcoming': 'bg-blue-500',
    'in-progress': 'bg-green-500',
    'completed': 'bg-gray-500',
    'closed': 'bg-red-500'
  };

  const participantStatusColors = {
    'approved': 'bg-green-500',
    'pending': 'bg-yellow-500',
    'rejected': 'bg-red-500'
  };

  const sponsorTypeColors = {
    'financial': 'bg-green-600',
    'media': 'bg-purple-600',
    'equipment': 'bg-blue-600',
    'other': 'bg-gray-600'
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-[#B8CAE0] via-[#D4E2F0] to-[#A0B8D8] p-6">
      <div className="max-w-7xl mx-auto flex gap-6">
        {/* Sidebar */}
        <Sidebar activeTab={activeTab} onTabChange={setActiveTab} />

        {/* Main Content */}
        <div className="flex-1 min-w-0">
          <DashboardHeader userName="Stella Walton" />

          {/* Dashboard Overview */}
          {activeTab === 'dashboard' && (
            <div className="space-y-6">
              {/* Welcome Card */}
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
              >
                <Card className="p-8 bg-gradient-to-br from-[#243B53] to-[#4E5F7C] text-[#F4EDE4] border-0 relative overflow-hidden shadow-2xl">
                  <div className="absolute top-0 right-0 w-40 h-40 bg-[#E8D5D9]/10 rounded-full -translate-y-1/2 translate-x-1/2" />
                  <div className="absolute bottom-0 left-0 w-32 h-32 bg-[#E8D5D9]/10 rounded-full translate-y-1/2 -translate-x-1/2" />
                  
                  <div className="relative z-10">
                    <motion.h2
                      className="text-3xl mb-2"
                      initial={{ opacity: 0, x: -20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: 0.2 }}
                    >
                      Event Management Dashboard
                    </motion.h2>
                    <motion.p
                      className="text-[#E8D5D9]/80 mb-1"
                      initial={{ opacity: 0, x: -20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: 0.3 }}
                    >
                      Efficiently manage events, participants, and sponsorships in one place.
                    </motion.p>
                    <motion.p
                      className="text-[#E8D5D9]/60 text-sm"
                      initial={{ opacity: 0, x: -20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: 0.35 }}
                    >
                      For Associations • Event Managers • Sponsors
                    </motion.p>
                  </div>
                </Card>
              </motion.div>

              {/* Quick Stats */}
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <StatCard
                  title="Total Events"
                  value={analyticsData.totalEvents}
                  icon={Calendar}
                  trend={`${upcomingEvents.length} upcoming`}
                  delay={0.1}
                />
                <StatCard
                  title="Total Participants"
                  value={analyticsData.totalParticipants}
                  icon={Users}
                  trend={`+${analyticsData.participationGrowth}% growth`}
                  color="text-green-600"
                  delay={0.2}
                />
                <StatCard
                  title="Active Sponsors"
                  value={analyticsData.totalSponsors}
                  icon={HandshakeIcon}
                  trend="8 new this month"
                  color="text-blue-600"
                  delay={0.3}
                />
                <StatCard
                  title="Pending Requests"
                  value={analyticsData.pendingRequests}
                  icon={Clock}
                  trend="Needs review"
                  color="text-yellow-600"
                  delay={0.4}
                />
              </div>

              {/* Quick Actions */}
              <div>
                <SectionTitle title="Quick Actions" icon={Zap} />
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <QuickActionCard
                    title="Create Event"
                    icon={Plus}
                    onClick={() => setShowCreateEvent(true)}
                    delay={0.5}
                  />
                  <QuickActionCard
                    title="View Participants"
                    icon={Eye}
                    onClick={() => setActiveTab('participants')}
                    delay={0.6}
                  />
                  <QuickActionCard
                    title="Add Sponsor"
                    icon={UserPlus}
                    onClick={() => setShowCreateSponsor(true)}
                    delay={0.7}
                  />
                  <QuickActionCard
                    title="Contact All"
                    icon={Mail}
                    onClick={() => console.log('Contact participants')}
                    delay={0.8}
                  />
                </div>
              </div>

              {/* Main Content Grid */}
              <div className="grid lg:grid-cols-3 gap-6">
                {/* Upcoming Events */}
                <div className="lg:col-span-2 space-y-4">
                  <div className="flex items-center justify-between">
                    <h3 className="text-[#243B53]">Upcoming Events Summary</h3>
                    <Button
                      variant="ghost"
                      onClick={() => setActiveTab('events')}
                      className="text-[#243B53]"
                    >
                      View All
                    </Button>
                  </div>
                  {upcomingEvents.map((event, index) => (
                    <EventCard key={event.id} event={event} delay={0.9 + index * 0.1} />
                  ))}
                </div>

                {/* Sidebar Widgets */}
                <div className="space-y-6">
                  {/* Event Calendar */}
                  <EventCalendar events={events} />
                  
                  {/* Recent Participants */}
                  <motion.div
                    initial={{ opacity: 0, x: 20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 0.9 }}
                  >
                    <Card className="p-5 bg-white border-2 border-[#6B85A8]/30 shadow-lg">
                      <div className="flex items-center gap-2 mb-4">
                        <Users className="h-5 w-5 text-[#243B53]" />
                        <h4 className="text-[#243B53]">Recent Participants</h4>
                      </div>
                      <div className="space-y-3">
                        {recentParticipants.map((participant, index) => (
                          <motion.div
                            key={participant.id}
                            className="flex items-center justify-between p-3 rounded-lg bg-[#F4EDE4]/50 hover:bg-[#E8D5D9]/30 transition-colors"
                            initial={{ opacity: 0, x: 20 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ delay: 1 + index * 0.1 }}
                            whileHover={{ x: 5 }}
                          >
                            <div className="flex items-center gap-3">
                              <div className="w-8 h-8 rounded-full bg-[#243B53] text-[#F4EDE4] flex items-center justify-center text-xs">
                                {participant.name.split(' ').map(n => n[0]).join('')}
                              </div>
                              <div>
                                <p className="text-[#243B53] text-sm">{participant.name}</p>
                                <p className="text-[#4E5F7C] text-xs">{participant.eventName}</p>
                              </div>
                            </div>
                            <Badge className={`${participantStatusColors[participant.status]} text-white text-xs`}>
                              {participant.status}
                            </Badge>
                          </motion.div>
                        ))}
                      </div>
                    </Card>
                  </motion.div>

                  {/* Sponsorship Highlights */}
                  <motion.div
                    initial={{ opacity: 0, x: 20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 1.2 }}
                  >
                    <Card className="p-5 bg-white border-2 border-[#6B85A8]/30 shadow-lg">
                      <div className="flex items-center gap-2 mb-4">
                        <HandshakeIcon className="h-5 w-5 text-[#243B53]" />
                        <h4 className="text-[#243B53]">Recent Sponsors</h4>
                      </div>
                      <div className="space-y-3">
                        {recentSponsors.map((sponsor, index) => (
                          <motion.div
                            key={sponsor.id}
                            className="p-3 rounded-lg bg-[#F4EDE4]/50 hover:bg-[#E8D5D9]/30 transition-colors"
                            initial={{ opacity: 0, x: 20 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ delay: 1.3 + index * 0.1 }}
                            whileHover={{ x: 5 }}
                          >
                            <div className="flex items-center justify-between mb-2">
                              <p className="text-[#243B53]">{sponsor.name}</p>
                              <Badge className={`${sponsorTypeColors[sponsor.type]} text-white text-xs`}>
                                {sponsor.type}
                              </Badge>
                            </div>
                            <p className="text-[#4E5F7C] text-xs">{sponsor.eventName}</p>
                          </motion.div>
                        ))}
                      </div>
                    </Card>
                  </motion.div>
                </div>
              </div>
            </div>
          )}

          {/* Events Management Section */}
          {activeTab === 'events' && (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="space-y-6"
            >
              <div className="flex items-center justify-between">
                <h2 className="text-[#243B53] text-2xl">Events Management</h2>
                <Button className="bg-[#243B53] text-[#F4EDE4] hover:bg-[#4E5F7C]">
                  <Plus className="h-4 w-4 mr-2" />
                  Create Event
                </Button>
              </div>

              <div className="grid md:grid-cols-2 gap-4">
                {events.map((event, index) => (
                  <motion.div
                    key={event.id}
                    initial={{ opacity: 0, scale: 0.95 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ delay: index * 0.1 }}
                  >
                    <Card className="p-5 bg-white border-2 border-[#6B85A8]/30 hover:border-[#243B53]/50 transition-all shadow-md">
                      <div className="flex items-start justify-between mb-3">
                        <div className="flex-1">
                          <h3 className="text-[#243B53] mb-2">{event.title}</h3>
                          <p className="text-[#4E5F7C] text-sm mb-3">{event.description}</p>
                        </div>
                        <Badge className={`${statusColors[event.status]} text-white ml-2`}>
                          {event.status}
                        </Badge>
                      </div>

                      <div className="space-y-2 mb-4 text-sm">
                        <div className="flex items-center gap-2 text-[#4E5F7C]">
                          <Calendar className="h-4 w-4" />
                          <span>{new Date(event.date).toLocaleDateString()}</span>
                          <span className="text-xs text-yellow-600 ml-2">
                            Deadline: {new Date(event.deadline).toLocaleDateString()}
                          </span>
                        </div>
                        <div className="flex items-center gap-2 text-[#4E5F7C]">
                          <Users className="h-4 w-4" />
                          <span>{event.participantCount} participants</span>
                        </div>
                        <div className="flex items-center gap-2 text-[#4E5F7C]">
                          <HandshakeIcon className="h-4 w-4" />
                          <span>{event.sponsorCount} sponsors</span>
                        </div>
                      </div>

                      <div className="flex gap-2">
                        <Button size="sm" variant="outline" className="flex-1">
                          <Edit className="h-3 w-3 mr-1" />
                          Edit
                        </Button>
                        <Button size="sm" variant="outline" className="flex-1">
                          <Eye className="h-3 w-3 mr-1" />
                          Details
                        </Button>
                        <Button size="sm" variant="outline" className="flex-1">
                          <Mail className="h-3 w-3 mr-1" />
                          Contact
                        </Button>
                      </div>
                    </Card>
                  </motion.div>
                ))}
              </div>
            </motion.div>
          )}

          {/* Participants Management */}
          {activeTab === 'participants' && (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="space-y-6"
            >
              <div className="flex items-center justify-between">
                <h2 className="text-[#243B53] text-2xl">Participant Management</h2>
                <div className="flex gap-2">
                  <Button variant="outline">Export List</Button>
                  <Button className="bg-[#243B53] text-[#F4EDE4]">
                    Filter
                  </Button>
                </div>
              </div>

              {pendingParticipants.length > 0 && (
                <Card className="p-5 bg-yellow-50 border-2 border-yellow-400">
                  <h3 className="text-[#243B53] mb-3">Pending Approval ({pendingParticipants.length})</h3>
                  <div className="space-y-2">
                    {pendingParticipants.map((participant) => (
                      <div
                        key={participant.id}
                        className="flex items-center justify-between p-3 bg-white rounded-lg"
                      >
                        <div>
                          <p className="text-[#243B53]">{participant.name}</p>
                          <p className="text-[#4E5F7C] text-sm">{participant.eventName}</p>
                        </div>
                        <div className="flex gap-2">
                          <Button size="sm" className="bg-green-600 hover:bg-green-700 text-white">
                            <CheckCircle className="h-4 w-4 mr-1" />
                            Approve
                          </Button>
                          <Button size="sm" variant="destructive">
                            <XCircle className="h-4 w-4 mr-1" />
                            Reject
                          </Button>
                        </div>
                      </div>
                    ))}
                  </div>
                </Card>
              )}

              <Card className="p-6 bg-white border-2 border-[#6B85A8]/30">
                <h3 className="text-[#243B53] mb-4">All Participants</h3>
                <div className="space-y-3">
                  {participants.map((participant, index) => (
                    <motion.div
                      key={participant.id}
                      className="flex items-center justify-between p-4 rounded-xl bg-[#F4EDE4]/50 hover:bg-[#E8D5D9]/30 transition-all"
                      initial={{ opacity: 0, x: -20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: index * 0.05 }}
                      whileHover={{ x: 10 }}
                    >
                      <div className="flex items-center gap-4">
                        <div className="w-12 h-12 rounded-full bg-[#243B53] text-[#F4EDE4] flex items-center justify-center">
                          {participant.name.split(' ').map(n => n[0]).join('')}
                        </div>
                        <div>
                          <p className="text-[#243B53]">{participant.name}</p>
                          <p className="text-[#4E5F7C] text-sm">{participant.email}</p>
                          <p className="text-[#6B85A8] text-xs">{participant.eventName}</p>
                        </div>
                      </div>
                      <div className="flex items-center gap-3">
                        <Badge className={`${participantStatusColors[participant.status]} text-white`}>
                          {participant.status}
                        </Badge>
                        <div className="flex gap-1">
                          <Button size="sm" variant="ghost">
                            <Phone className="h-4 w-4" />
                          </Button>
                          <Button size="sm" variant="ghost">
                            <Mail className="h-4 w-4" />
                          </Button>
                        </div>
                      </div>
                    </motion.div>
                  ))}
                </div>
              </Card>
            </motion.div>
          )}

          {/* Sponsors Management */}
          {activeTab === 'sponsors' && (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="space-y-6"
            >
              <div className="flex items-center justify-between">
                <h2 className="text-[#243B53] text-2xl">Sponsor Management</h2>
                <Button className="bg-[#243B53] text-[#F4EDE4] hover:bg-[#4E5F7C]">
                  <Plus className="h-4 w-4 mr-2" />
                  Add Sponsor
                </Button>
              </div>

              <div className="grid md:grid-cols-2 gap-4">
                {sponsors.map((sponsor, index) => (
                  <motion.div
                    key={sponsor.id}
                    initial={{ opacity: 0, scale: 0.95 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ delay: index * 0.1 }}
                  >
                    <Card className="p-5 bg-white border-2 border-[#6B85A8]/30 hover:border-[#243B53]/50 transition-all shadow-md">
                      <div className="flex items-start justify-between mb-3">
                        <div>
                          <h3 className="text-[#243B53] mb-1">{sponsor.name}</h3>
                          <p className="text-[#4E5F7C] text-sm">{sponsor.eventName}</p>
                        </div>
                        <Badge className={`${sponsorTypeColors[sponsor.type]} text-white`}>
                          {sponsor.type}
                        </Badge>
                      </div>

                      <div className="space-y-2 mb-4 text-sm text-[#4E5F7C]">
                        <div className="flex items-center gap-2">
                          <Mail className="h-4 w-4" />
                          <span>{sponsor.contactEmail}</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <Phone className="h-4 w-4" />
                          <span>{sponsor.contactPhone}</span>
                        </div>
                        <p className="text-xs pt-2 border-t">{sponsor.contributionNotes}</p>
                        {sponsor.contractStatus && (
                          <Badge variant="outline" className="mt-2">
                            Contract: {sponsor.contractStatus}
                          </Badge>
                        )}
                      </div>

                      <div className="flex gap-2">
                        <Button size="sm" variant="outline" className="flex-1">
                          <Edit className="h-3 w-3 mr-1" />
                          Edit
                        </Button>
                        <Button size="sm" variant="outline" className="flex-1">
                          <Eye className="h-3 w-3 mr-1" />
                          Details
                        </Button>
                        <Button size="sm" variant="outline" className="flex-1">
                          <Mail className="h-3 w-3 mr-1" />
                          Contact
                        </Button>
                      </div>
                    </Card>
                  </motion.div>
                ))}
              </div>
            </motion.div>
          )}

          {/* Analytics & Insights */}
          {activeTab === 'analytics' && (
            <AnalyticsDashboard />
          )}

          {/* Admin Tasks */}
          {activeTab === 'tasks' && (
            <TaskBoard />
          )}

          {/* Admin Tools */}
          {activeTab === 'settings' && (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="space-y-6"
            >
              <h2 className="text-[#243B53] text-2xl">Admin Tools</h2>

              <div className="grid md:grid-cols-2 gap-6">
                <Card className="p-6 bg-white border-2 border-[#6B85A8]/30">
                  <h3 className="text-[#243B53] mb-4">Team Management</h3>
                  <p className="text-[#4E5F7C] mb-4">Manage team members and assign event managers</p>
                  <Button className="w-full bg-[#243B53] text-[#F4EDE4]">
                    Manage Team
                  </Button>
                </Card>

                <Card className="p-6 bg-white border-2 border-[#6B85A8]/30">
                  <h3 className="text-[#243B53] mb-4">Event Visibility</h3>
                  <p className="text-[#4E5F7C] mb-4">Control event visibility and access settings</p>
                  <Button className="w-full bg-[#243B53] text-[#F4EDE4]">
                    Configure Settings
                  </Button>
                </Card>

                <Card className="p-6 bg-white border-2 border-[#6B85A8]/30">
                  <h3 className="text-[#243B53] mb-4">Archive Events</h3>
                  <p className="text-[#4E5F7C] mb-4">Archive old events to keep dashboard clean</p>
                  <Button className="w-full bg-[#243B53] text-[#F4EDE4]">
                    View Archive
                  </Button>
                </Card>

                <Card className="p-6 bg-white border-2 border-[#6B85A8]/30">
                  <h3 className="text-[#243B53] mb-4">Roles & Permissions</h3>
                  <p className="text-[#4E5F7C] mb-4">Configure user roles and permission levels</p>
                  <Button className="w-full bg-[#243B53] text-[#F4EDE4]">
                    Manage Permissions
                  </Button>
                </Card>
              </div>

              <Card className="p-8 bg-gradient-to-br from-[#E8D5D9] to-[#F4EDE4] border-2 border-[#6B85A8]/30 text-center">
                <motion.div
                  animate={{ rotate: [0, 10, -10, 0] }}
                  transition={{ duration: 2, repeat: Infinity }}
                  className="inline-block mb-4"
                >
                  <StarIcon className="w-16 h-16 text-[#243B53]" />
                </motion.div>
                <h3 className="text-[#243B53] mb-2">System Status: All Systems Operational</h3>
                <p className="text-[#4E5F7C]">Last updated: {new Date().toLocaleString()}</p>
              </Card>
            </motion.div>
          )}
        </div>
      </div>

      {/* Create Event Form */}
      <CreateEventForm open={showCreateEvent} onClose={() => setShowCreateEvent(false)} />

      {/* Create Sponsor Form */}
      <CreateSponsorForm open={showCreateSponsor} onClose={() => setShowCreateSponsor(false)} />
    </div>
  );
}