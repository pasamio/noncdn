# noncdn

noncdn is a CDN aimed at ensuring that all accesses to files at edge nodes are authenticated and logged appropriately. A response to non-authenticating CDN's such as Facebook's which permit direct access to files on their CDN to anyone who knows the URL, noncdn aims to provide a lightweight mechanism to provide basic access control and logging. 



## Aims
### Lightweight access control
The basic unit that noncdn's authorisation system works on is a `container`. A container is a logical storage unit for items and for granting access. In the aim of efficiency, there is no concept of fine grained access control. The concept is to enable simplicity in management and enforcement. Systems that implement much heavier access controls typically do so at the cost of performance. A great example of this is NTFS.

noncdn doesn't aim to be bullet proof solution as that would be too heavy weight however it does aim to provide logging support to enable analysis to detect potentially abusive situations.



### Distributed architecture
Distributed architecture is a key component of a CDN, noncdn also aims to have each of the three major components designed in such a way that they are specialised enough to handle their own situation which can improve reliability.

Locality to client for edge nodes is an important feature. The project is not aimed at handling small files but larger files where the latency overhead of the architecture is less of an issue. The aim is to build edge nodes that are local to the end user to improve their overall throughput.



### Auditing
The system aims to audit all accesses from end users at master nodes and edge nodes to capture situations of abuse and to provide compliance with data access monitoring and insider trading regulations. 

Auditing is enforced at the master and edge nodes when ever content is accessed to enable tracking of abuse and malicious activity.



### De-duplication
A key aim of the system is the ability to transparently de-duplicate content within the system. This initially was a design factor for the situation where content was being uploaded more than once into a previous content delivery system. This resulted in more storage being utilised than necessary to store the file.

However as auditing was added to the system, de-duplication of content provides an extra benefit: the ability to detect when content is being shared in an unauthorised manner.

De-duplicated content is tracked within the auditing system with both the de-duplicated unique content identifier and with the logical access identifier from the container. This permits detection of situations where content that should be protected has been re-uploaded with access controls reduced through the examination of the auditing system.



## Architecture

noncdn is split into three main parts: master nodes, authentication nodes and edge nodes. 

### Master Nodes
Master nodes are central to the system and provide the functionality to co-ordinate access. All links within the system are pointed to the master nodes which then handle authenticating the client and redirecting them to edge nodes.

Master nodes store a copy of all of the files which they deliver to edge nodes on demand. Master nodes attempt to de-duplicate content and provide unique identifiers for files to reduce data duplication and improve the ability for edge nodes to deliver files from their cache.

### Authentication Nodes
Authentication nodes are designed to handle authenticating end users and authorising access to given containers. Authentication nodes may in turn depend upon other services (e.g. an SSO provider or LDAP directory) however this is abstracted away within the system.


### Edge Nodes
Edge nodes are located near the client and are aimed at providing high throughput for the client or other beneficial services (e.g. free transfers due to peering). Edge nodes are supplied with fixed tokens which are used to authenticate requests. If the request token is valid the edge nodes then retrieve a list of authorisations for a given user from the authentication nodes prior to handling the request. If the user is authenticated but not authorised, the request will be denied. Finally, edge nodes maintain a local cache of files which it can supply to end users. If the file is in the cache then the request will be delivered from cache however if the file is not in the cache it will be retrieved from the master node to be cached locally and delivered to the client.


## Use Cases
There are various use cases that this project is attempting to resolve.

### Case 1: Media rich course materials
As universities become more online enabled, the demand for media rich course material which include video presentations, audio presentations and other material increases. The cost of delivering this in an effective manner becomes a differentiator for the provider.

noncdn's container structure can effectively map into course codes and structures to permit access for students to resources and the teaching academics access as well. The simple design reduces access control complications and makes it clear which students and academic members can access resources.


### Case 2: Business Intelligence Reporting
Increasingly business intelligence reporting is using larger amounts of data that ar transferred to the client to permit interactive examination of the data stored in a data warehouse. As data warehouses are moved into larger structures they can move further away from the need users which means that the effective throughput to the client from the data warehouse is reduced. This effect can be multiplied for organisations who have analysts on multiple continents and don't have the data warehouses to match each of the geographical locations that those clients are located.

noncdn can provide edge support close to the client to improve the overall delivery speeds while ensuring that organisational integrity and reporting for Sarbanes-Oxley compliance is maintained appropriately through auditing access. Access irregularities can also be discovered through examination of the log files.
